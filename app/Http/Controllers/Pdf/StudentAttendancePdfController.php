<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\OpsStudentAttendance;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentAttendancePdfController extends Controller
{
    use PdfPreviewable;

    /**
     * Helper untuk validasi hak akses dan kepemilikan kelas guru yang login
     */
    private function validateClassAccess(SchoolClass $class)
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

        // Jika bukan admin/headmaster, pastikan guru yang login adalah wali kelas dari kelas tersebut
        if (!$isAdminOrHeadmaster && $teacher) {
            $homeroomId = $class->homeroom_teacher_id ?? optional($class->homeroomTeacher)->id;
            
            if ($homeroomId !== $teacher->id) {
                abort(403, 'Anda tidak memiliki hak akses untuk mencetak atau melihat data presensi kelas ini.');
            }
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return $teacher;
    }

    /** Laporan Matriks Presensi Bulanan (Per Tanggal 1 s.d. 31) */
    public function monthlyPdf(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:acad_classes,id',
            'month'      => 'nullable|integer|between:1,12',
            'year'       => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        Carbon::setLocale('id');

        if ($request->filled('month') && $request->filled('year')) {
            $month = (int) $request->month;
            $year  = (int) $request->year;
        } elseif ($request->filled('start_date')) {
            $parsed = Carbon::parse($request->start_date);
            $month  = $parsed->month;
            $year   = $parsed->year;
        } else {
            $month = (int) now()->month;
            $year  = (int) now()->year;
        }

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $class = SchoolClass::with('homeroomTeacher')->findOrFail($request->class_id);
        
        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $school = SchoolProfile::first();

        $students = Student::where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get();

        $attendances = OpsStudentAttendance::where('class_id', $request->class_id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $attendanceData = [];
        $dailyTotals    = [];
        $grandTotalSakit = 0;
        $grandTotalIzin  = 0;
        $grandTotalAlpa  = 0;

        foreach ($attendances as $attendance) {
            $dateKey   = Carbon::parse($attendance->date)->format('Y-m-d');
            $studentId = $attendance->student_id;
            $statusChar = strtoupper(substr($attendance->status, 0, 1));

            if (in_array($statusChar, ['S', 'I', 'A'])) {
                $attendanceData[$studentId][$dateKey] = $statusChar;
                $dailyTotals[$dateKey] = ($dailyTotals[$dateKey] ?? 0) + 1;

                if ($statusChar === 'S') $grandTotalSakit++;
                elseif ($statusChar === 'I') $grandTotalIzin++;
                elseif ($statusChar === 'A') $grandTotalAlpa++;
            }
        }

        $titiMangsa = $endDate->translatedFormat('d F Y');

        $pdf = Pdf::loadView('pdf.laporan-presensi-bulanan', [
            'class'           => $class,
            'students'        => $students,
            'attendanceData'  => $attendanceData,
            'dailyTotals'     => $dailyTotals,
            'grandTotalSakit' => $grandTotalSakit,
            'grandTotalIzin'  => $grandTotalIzin,
            'grandTotalAlpa'  => $grandTotalAlpa,
            'month'           => $month,
            'year'            => $year,
            'daysInMonth'     => $daysInMonth,
            'startDate'       => $startDate->format('Y-m-d'),
            'endDate'         => $endDate->format('Y-m-d'),
            'school'          => $school,
            'headmaster'      => $headmaster,
            'teacher'         => $teacher,
            'titiMangsa'      => $titiMangsa,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Presensi_Bulanan_{$class->name}_{$month}_{$year}.pdf");
    }

    /** Laporan Rekapitulasi Persentase Kehadiran (ringkasan) */
    public function __invoke(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:acad_classes,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::with('homeroomTeacher')->findOrFail($request->class_id);
        
        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $school = SchoolProfile::first();

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);

        $effectiveDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $effectiveDays++;
            }
            $currentDate->addDay();
        }

        $students = Student::where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get();

        $attendances = OpsStudentAttendance::where('class_id', $request->class_id)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->get();

        $summary = $students->map(function ($student) use ($attendances, $effectiveDays) {
            $studentAttendances = $attendances->where('student_id', $student->id);

            $sakit = $studentAttendances->whereIn('status', ['Sakit', 'S'])->count();
            $izin  = $studentAttendances->whereIn('status', ['Izin', 'I'])->count();
            $alpa  = $studentAttendances->whereIn('status', ['Alpa', 'A'])->count();

            $totalKetidakhadiran = $sakit + $izin + $alpa;
            $hadir = max(0, $effectiveDays - $totalKetidakhadiran);
            $percentage = $effectiveDays > 0 ? round(($hadir / $effectiveDays) * 100, 1) : 0;

            return [
                'student'    => $student,
                'hadir'      => $hadir,
                'sakit'      => $sakit,
                'izin'       => $izin,
                'alpa'       => $alpa,
                'total'      => $totalKetidakhadiran,
                'percentage' => $percentage,
            ];
        });

        $titiMangsa = $endDate->translatedFormat('d F Y');
        $monthName  = $startDate->translatedFormat('F Y');

        $pdf = Pdf::loadView('pdf.laporan-presensi-siswa', [
            'class'         => $class,
            'attendances'   => $attendances,
            'summary'       => $summary,
            'startDate'     => $request->start_date,
            'endDate'       => $request->end_date,
            'effectiveDays' => $effectiveDays,
            'monthName'     => $monthName,
            'school'        => $school,
            'headmaster'    => $headmaster,
            'teacher'       => $teacher,
            'titiMangsa'    => $titiMangsa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Laporan_Persentase_Kehadiran_{$class->name}.pdf");
    }

    public function preview(Request $request)
    {
        return $this->__invoke($request);
    }
}