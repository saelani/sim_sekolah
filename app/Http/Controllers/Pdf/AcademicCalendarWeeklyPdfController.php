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

class AcademicCalendarWeeklyPdfController extends Controller
{
    use PdfPreviewable;

    public function generatePdf(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:acad_classes,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'subject_id' => 'nullable|exists:acad_subjects,id',
        ]);

        Carbon::setLocale('id');

        $user = auth()->user();
        $teacher = null;
        if ($user) {
            $teacher = $user->teacher ?? (isset($user->teacher_id) ? Teacher::find($user->teacher_id) : null);
        }
        if ($teacher instanceof \Illuminate\Support\Collection) {
            $teacher = $teacher->first();
        }

        // 🛡️ TERAPKAN LOGIKA FILTER YANG SAMA DENGAN RESOURCE
        $query = AcademicCalendar::with(['learningObjective', 'subject'])
            ->where('class_id', $request->class_id)
            ->whereBetween('start_date', [$request->start_date, $request->end_date]);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Jika user bukan Admin / Headmaster, batasi dengan filter kepemilikan
        $isAdminOrHeadmaster = false;
        if ($user) {
            // Cek role menggunakan method bawaan Spatie/Filament atau role_type guru
            if (method_exists($user, 'hasRole') && ($user->role === 'admin' || $user->role === 'headmaster')) {
                $isAdminOrHeadmaster = true;
            } elseif ($teacher && $teacher->role_type === 'headmaster') {
                $isAdminOrHeadmaster = true;
            }
        }

        if (!$isAdminOrHeadmaster && $teacher) {
            $teacherId = $teacher->id;
            
            // Filter ketat persis seperti di Resource
            $query->whereHas('learningObjective', function ($subQuery) use ($teacherId) {
                $subQuery->where('teacher_id', $teacherId)
                         ->orWhereHas('sumativeScope', function ($scopeQuery) use ($teacherId) {
                             $scopeQuery->where('teacher_id', $teacherId);
                         });
            });
        } elseif (!$isAdminOrHeadmaster) {
            // Jika bukan admin/headmaster dan data teacher tidak valid, blokir
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        $schedules = $query->orderBy('start_date', 'asc')->get();

        // Jika setelah difilter ternyata data kosong (berarti mencoba mencetak data orang lain)
        if ($schedules->isEmpty() && !$isAdminOrHeadmaster) {
            abort(403, 'Anda tidak memiliki hak akses atau data ini bukan milik Anda.');
        }

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = $request->subject_id ? Subject::find($request->subject_id) : null;
        $school = SchoolProfile::first() ?? new SchoolProfile();

        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $pdf = Pdf::loadView('pdf.academic-calendar-weekly', [
            'class'      => $class,
            'subject'    => $subject,
            'startDate'  => $request->start_date,
            'endDate'    => $request->end_date,
            'schedules'  => $schedules,
            'school'     => $school,
            'headmaster' => $headmaster,
            'teacher'    => $teacher,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Jadwal_Mingguan_{$class->name}.pdf");
    }

    public function preview(Request $request)
    {
        // Gunakan logika persis yang sama untuk preview
        return $this->generatePdf($request); // Bisa langsung diarahkan ke generatePdf atau disamakan blok query-nya
    }
}