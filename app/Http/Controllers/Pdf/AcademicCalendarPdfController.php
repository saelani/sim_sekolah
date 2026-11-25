<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\AcademicCalendar;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Subject;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AcademicCalendarPdfController extends Controller
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

        // Jika bukan Admin/Headmaster, terapkan filter ketat relasi learningObjective & sumativeScope (seperti di Resource)
        if (!$isAdminOrHeadmaster && $teacher) {
            $teacherId = $teacher->id;
            
            $query->whereHas('learningObjective', function ($subQuery) use ($teacherId) {
                $subQuery->where('teacher_id', $teacherId)
                         ->orWhereHas('sumativeScope', function ($scopeQuery) use ($teacherId) {
                             $scopeQuery->where('teacher_id', $teacherId);
                         });
            });
        } elseif (!$isAdminOrHeadmaster) {
            // Jika bukan admin/headmaster dan data teacher tidak valid
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return [$teacher, $isAdminOrHeadmaster];
    }

    /** Laporan Semester / Bulanan (Matrix Minggu 1-5) */
    public function __invoke(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:acad_classes,id',
            'semester'    => 'required|in:1,2',
            'year'        => 'required|numeric',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }
        $school = SchoolProfile::first();

        $year = (int) $request->year;

        if ($request->semester == '1') {
            $monthsList = [
                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $startDate = "{$year}-07-01";
            $endDate   = "{$year}-12-31";
        } else {
            $monthsList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                4 => 'April', 5 => 'Mei', 6 => 'Juni',
            ];
            $startDate = "{$year}-01-01";
            $endDate   = "{$year}-06-30";
        }

        $query = AcademicCalendar::with(['learningObjective', 'subject'])
            ->where('class_id', $request->class_id)
            ->whereBetween('start_date', [$startDate, $endDate]);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Terapkan pengamanan & filter kepemilikan
        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $events = $query->orderBy('start_date', 'asc')->get();

        // Jika data kosong setelah difilter dan bukan admin/headmaster, cegah akses
        if ($events->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau data ini bukan milik Anda.');
        }

        $monthlyMatrix = [];
        foreach ($monthsList as $mNum => $mName) {
            $monthlyMatrix[$mNum] = [
                'name' => $mName,
                'weeks' => [1 => [], 2 => [], 3 => [], 4 => [], 5 => []],
            ];
        }

        foreach ($events as $event) {
            $eventStart = Carbon::parse($event->start_date);
            $mNum = $eventStart->month;

            if (isset($monthlyMatrix[$mNum])) {
                $dayOfMonth = $eventStart->day;
                $weekNumber = (int) ceil($dayOfMonth / 7);
                if ($weekNumber > 5) {
                    $weekNumber = 5;
                }

                $monthlyMatrix[$mNum]['weeks'][$weekNumber][] = $event;
            }
        }

        $pdf = Pdf::loadView('pdf.laporan-kalender-pembelajaran', [
            'class'        => $class,
            'subject'      => $subject,
            'semester'     => $request->semester,
            'year'         => $year,
            'monthlyMatrix'=> $monthlyMatrix,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Pemetaan_Pembelajaran_{$class->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:acad_classes,id',
            'semester'    => 'required|in:1,2',
            'year'        => 'required|numeric',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }
        $school = SchoolProfile::first();

        $year = (int) $request->year;

        if ($request->semester == '1') {
            $monthsList = [
                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $startDate = "{$year}-07-01";
            $endDate   = "{$year}-12-31";
        } else {
            $monthsList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                4 => 'April', 5 => 'Mei', 6 => 'Juni',
            ];
            $startDate = "{$year}-01-01";
            $endDate   = "{$year}-06-30";
        }

        $query = AcademicCalendar::with(['learningObjective', 'subject'])
            ->where('class_id', $request->class_id)
            ->whereBetween('start_date', [$startDate, $endDate]);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $events = $query->orderBy('start_date', 'asc')->get();

        if ($events->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau preview data ini bukan milik Anda.');
        }

        $monthlyMatrix = [];
        foreach ($monthsList as $mNum => $mName) {
            $monthlyMatrix[$mNum] = [
                'name' => $mName,
                'weeks' => [1 => [], 2 => [], 3 => [], 4 => [], 5 => []],
            ];
        }

        foreach ($events as $event) {
            $eventStart = Carbon::parse($event->start_date);
            $mNum = $eventStart->month;

            if (isset($monthlyMatrix[$mNum])) {
                $dayOfMonth = $eventStart->day;
                $weekNumber = (int) ceil($dayOfMonth / 7);
                if ($weekNumber > 5) {
                    $weekNumber = 5;
                }

                $monthlyMatrix[$mNum]['weeks'][$weekNumber][] = $event;
            }
        }

        return $this->previewPdf('pdf.laporan-kalender-pembelajaran', [
            'class'        => $class,
            'subject'      => $subject,
            'semester'     => $request->semester,
            'year'         => $year,
            'monthlyMatrix'=> $monthlyMatrix,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
        ], "Pemetaan_Pembelajaran_{$class->name}", 'landscape');
    }

    /**
     * Preview Mingguan (inline)
     */
    public function previewWeeklyPdf(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:acad_classes,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }
        $school = SchoolProfile::first();

        $query = AcademicCalendar::with(['learningObjective', 'subject'])
            ->where('class_id', $request->class_id)
            ->whereBetween('start_date', [$request->start_date, $request->end_date]);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        [$teacher, $isAdminOrHeadmaster] = $this->authorizeAndFilterQuery($request, $query);

        $schedules = $query->orderBy('start_date', 'asc')->get();

        if ($schedules->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau preview data ini bukan milik Anda.');
        }

        return $this->previewPdf('pdf.academic-calendar-weekly', [
            'class'        => $class,
            'subject'      => $subject,
            'schedules'    => $schedules,
            'startDate'    => $request->start_date,
            'endDate'      => $request->end_date,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
        ], "Laporan_Mingguan_{$class->name}", 'landscape');
    }
}