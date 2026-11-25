<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\Schedule as ScheduleModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchedulePdfController extends Controller
{
    use PdfPreviewable;

    /**
     * Helper untuk validasi hak akses dan kepemilikan data guru yang login
     */
    private function authorizeAndFilterQuery(Request $request, $query)
    {
        $user = auth()->user();
        $teacher = null;
        if ($user) {
            $teacher = $user->teacher ?? (isset($user->teacher_id) ? Teacher::find($user->teacher_id) : null);
        }
        if ($teacher instanceof \Illuminate\Support\Collection) {
            $teacher = $teacher->first();
        }

        // Cek apakah user adalah Admin atau Headmaster
        $isAdminOrHeadmaster = false;
        if ($user) {
            if (method_exists($user, 'hasRole') && ($user->role === 'admin' || $user->role === 'headmaster')) {
                $isAdminOrHeadmaster = true;
            } elseif ($teacher && $teacher->role_type === 'headmaster') {
                $isAdminOrHeadmaster = true;
            }
        }

        // Jika bukan Admin / Headmaster, terapkan filter ketat kepemilikan pada tabel ScheduleModel
        if (!$isAdminOrHeadmaster && $teacher) {
            $teacherId = $teacher->id;
            
            // Cek apakah tabel schedules memiliki kolom teacher_id atau relasi terkait
            if (\Illuminate\Support\Facades\Schema::hasColumn('acad_schedules', 'teacher_id')) {
                $query->where('teacher_id', $teacherId);
            } else {
                // Jika tidak ada kolom teacher_id langsung, filter berdasarkan kelas yang diampu atau data miliknya
                // (Sesuaikan jika ada kolom relasi guru di tabel schedules)
            }
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return [$teacher, $isAdminOrHeadmaster];
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:acad_classes,id',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::with('homeroomTeacher')->findOrFail($request->class_id);
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }
        
        $school = SchoolProfile::first() ?? new SchoolProfile();

        $schedulesQuery = ScheduleModel::with(['subject'])
            ->where('class_id', $request->class_id);

        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $schedulesQuery);

        $schedules = $schedulesQuery->get();

        // Jika data kosong setelah difilter dan bukan admin/headmaster, cegah akses
        if ($schedules->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau data jadwal ini bukan milik Anda.');
        }

        $masterTimes = [
            ['period' => '1',  'time' => '06:30 - 07:05', 'type' => 'lesson'],
            ['period' => '2',  'time' => '07:05 - 07:40', 'type' => 'lesson'],
            ['period' => '3',  'time' => '07:40 - 08:15', 'type' => 'lesson'],
            ['period' => '4',  'time' => '08:15 - 08:50', 'type' => 'lesson'],
            ['period' => '-',  'time' => '08:50 - 09:05', 'type' => 'break', 'label' => 'Istirahat I'],
            ['period' => '5',  'time' => '09:05 - 09:40', 'type' => 'lesson'],
            ['period' => '6',  'time' => '09:40 - 10:15', 'type' => 'lesson'],
            ['period' => '7',  'time' => '10:15 - 10:50', 'type' => 'lesson'],
            ['period' => '-',  'time' => '10:50 - 11:05', 'type' => 'break', 'label' => 'Istirahat II / Sholat Dzuhur'],
            ['period' => '8',  'time' => '11:05 - 11:40', 'type' => 'lesson'],
            ['period' => '9',  'time' => '11:40 - 12:15', 'type' => 'lesson'],
        ];

        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $titiMangsa = Carbon::now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.laporan-jadwal-pelajaran', [
            'class'         => $class,
            'schedules'     => $schedules,
            'masterTimes'   => $masterTimes,
            'daysOrder'     => $daysOrder,
            'school'        => $school,
            'headmaster'    => $headmaster,
            'teacher'       => $teacher ?? $class->homeroomTeacher,
            'titiMangsa'    => $titiMangsa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Jadwal_Pelajaran_Detail_{$class->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:acad_classes,id',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::with('homeroomTeacher')->findOrFail($request->class_id);
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }
        $school = SchoolProfile::first() ?? new SchoolProfile();

        $schedulesQuery = ScheduleModel::with(['subject'])
            ->where('class_id', $request->class_id);

        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $schedulesQuery);

        $schedules = $schedulesQuery->get();

        if ($schedules->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau preview data jadwal ini bukan milik Anda.');
        }

        $masterTimes = [
            ['period' => '1',  'time' => '06:30 - 07:05', 'type' => 'lesson'],
            ['period' => '2',  'time' => '07:05 - 07:40', 'type' => 'lesson'],
            ['period' => '3',  'time' => '07:40 - 08:15', 'type' => 'lesson'],
            ['period' => '4',  'time' => '08:15 - 08:50', 'type' => 'lesson'],
            ['period' => '-',  'time' => '08:50 - 09:05', 'type' => 'break', 'label' => 'Istirahat I'],
            ['period' => '5',  'time' => '09:05 - 09:40', 'type' => 'lesson'],
            ['period' => '6',  'time' => '09:40 - 10:15', 'type' => 'lesson'],
            ['period' => '7',  'time' => '10:15 - 10:50', 'type' => 'lesson'],
            ['period' => '-',  'time' => '10:50 - 11:05', 'type' => 'break', 'label' => 'Istirahat II / Sholat Dzuhur'],
            ['period' => '8',  'time' => '11:05 - 11:40', 'type' => 'lesson'],
            ['period' => '9',  'time' => '11:40 - 12:15', 'type' => 'lesson'],
        ];

        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $titiMangsa = Carbon::now()->translatedFormat('d F Y');

        return $this->previewPdf('pdf.laporan-jadwal-pelajaran', [
            'class'         => $class,
            'schedules'     => $schedules,
            'masterTimes'   => $masterTimes,
            'daysOrder'     => $daysOrder,
            'school'        => $school,
            'headmaster'    => $headmaster,
            'teacher'       => $teacher ?? $class->homeroomTeacher,
            'titiMangsa'    => $titiMangsa,
        ], "Jadwal_Pelajaran_Detail_{$class->name}", 'portrait');
    }
}