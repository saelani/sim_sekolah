<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentAgePdfController extends Controller
{
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
            // Asumsi relasi kelas dipegang oleh homeroom_teacher_id atau sejenisnya, 
            // atau dicocokkan langsung dengan id teacher pada homeroomTeacher.
            $homeroomId = $class->homeroom_teacher_id ?? optional($class->homeroomTeacher)->id;
            
            if ($homeroomId !== $teacher->id) {
                abort(403, 'Anda tidak memiliki hak akses untuk mencetak atau melihat data kelas ini.');
            }
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return $teacher;
    }

    /** Laporan Rekapitulasi Usia Siswa Bulanan */
    public function monthlyPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'class_id' => 'required',
            'month'    => 'required|integer|between:1,12',
            'year'     => 'required|integer',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::find($request->class_id);
        if (!$class) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $school = SchoolProfile::first();
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $month = (int) $request->month;
        $year  = (int) $request->year;

        $targetDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $students = Student::where('class_id', $request->class_id)
            ->get()
            ->makeHidden(['photo_url']);

        $ageRanges = range(6, 14);

        $matrix = [
            'L' => array_fill_keys($ageRanges, 0),
            'P' => array_fill_keys($ageRanges, 0),
        ];

        foreach ($students as $student) {
            if (!empty($student->dob)) {
                try {
                    $birthDate = $student->dob instanceof Carbon
                        ? $student->dob
                        : Carbon::parse($student->dob);

                    if ($birthDate->greaterThanOrEqualTo($targetDate)) {
                        continue;
                    }

                    $age = (int) $birthDate->diffInYears($targetDate);
                    $gender = strtoupper(trim($student->gender ?? ''));

                    if (in_array($gender, ['L', 'P']) && isset($matrix[$gender][$age])) {
                        $matrix[$gender][$age]++;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        $pdf = Pdf::loadView('pdf.laporan-usia-bulanan', [
            'class'        => $class,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
            'year'         => $year,
            'month'        => $month,
            'monthName'    => $targetDate->translatedFormat('F Y'),
            'matrix'       => $matrix,
            'ageRanges'    => $ageRanges,
            'titiMangsa'   => $targetDate->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Usia_Bulanan_{$class->name}.pdf");
    }

    /** Laporan Rekapitulasi Usia Siswa Tahunan (Juli - Juni) */
    public function yearlyPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'class_id' => 'required',
            'year'     => 'required|integer',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::find($request->class_id);
        if (!$class) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $school = SchoolProfile::first();
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $startYear = (int) $request->year;
        $academicYear = "{$startYear}/".($startYear + 1);

        $academicMonths = [
            ['m' => 7,  'y' => $startYear],
            ['m' => 8,  'y' => $startYear],
            ['m' => 9,  'y' => $startYear],
            ['m' => 10, 'y' => $startYear],
            ['m' => 11, 'y' => $startYear],
            ['m' => 12, 'y' => $startYear],
            ['m' => 1,  'y' => $startYear + 1],
            ['m' => 2,  'y' => $startYear + 1],
            ['m' => 3,  'y' => $startYear + 1],
            ['m' => 4,  'y' => $startYear + 1],
            ['m' => 5,  'y' => $startYear + 1],
            ['m' => 6,  'y' => $startYear + 1],
        ];

        $students = Student::where('class_id', $request->class_id)
            ->get()
            ->makeHidden(['photo_url']);

        $ageRanges = range(6, 14);
        $monthlyData = [];

        foreach ($academicMonths as $item) {
            $targetDate = Carbon::createFromDate($item['y'], $item['m'], 1)->endOfMonth();

            $matrix = [
                'L' => array_fill_keys($ageRanges, 0),
                'P' => array_fill_keys($ageRanges, 0),
            ];

            foreach ($students as $student) {
                if (!empty($student->dob)) {
                    try {
                        $birthDate = $student->dob instanceof Carbon
                            ? $student->dob
                            : Carbon::parse($student->dob);

                        if ($birthDate->greaterThanOrEqualTo($targetDate)) {
                            continue;
                        }

                        $age = (int) $birthDate->diffInYears($targetDate);
                        $gender = strtoupper(trim($student->gender ?? ''));

                        if (in_array($gender, ['L', 'P']) && isset($matrix[$gender][$age])) {
                            $matrix[$gender][$age]++;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            }

            $monthlyData[] = [
                'month_name' => $targetDate->translatedFormat('F Y'),
                'matrix'     => $matrix,
            ];
        }

        $lastMonthDate = Carbon::createFromDate($startYear + 1, 6, 1)->endOfMonth();

        $pdf = Pdf::loadView('pdf.laporan-usia-tahunan', [
            'class'        => $class,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
            'academicYear' => $academicYear,
            'monthlyData'  => $monthlyData,
            'ageRanges'    => $ageRanges,
            'titiMangsa'   => $lastMonthDate->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Usia_Tahunan_{$class->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'class_id' => 'required',
            'month'    => 'required|integer|between:1,12',
            'year'     => 'required|integer',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::find($request->class_id);
        if (!$class) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $school = SchoolProfile::first();
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $month = (int) $request->month;
        $year  = (int) $request->year;

        $targetDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $students = Student::where('class_id', $request->class_id)
            ->get()
            ->makeHidden(['photo_url']);

        $ageRanges = range(6, 14);

        $matrix = [
            'L' => array_fill_keys($ageRanges, 0),
            'P' => array_fill_keys($ageRanges, 0),
        ];

        foreach ($students as $student) {
            if (!empty($student->dob)) {
                try {
                    $birthDate = $student->dob instanceof Carbon
                        ? $student->dob
                        : Carbon::parse($student->dob);

                    if ($birthDate->greaterThanOrEqualTo($targetDate)) {
                        continue;
                    }

                    $age = (int) $birthDate->diffInYears($targetDate);
                    $gender = strtoupper(trim($student->gender ?? ''));

                    if (in_array($gender, ['L', 'P']) && isset($matrix[$gender][$age])) {
                        $matrix[$gender][$age]++;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        return $this->previewPdf('pdf.laporan-usia-bulanan', [
            'class'        => $class,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
            'year'         => $year,
            'month'        => $month,
            'monthName'    => $targetDate->translatedFormat('F Y'),
            'matrix'       => $matrix,
            'ageRanges'    => $ageRanges,
            'titiMangsa'   => $targetDate->translatedFormat('d F Y'),
        ], "Laporan_Usia_Bulanan_{$class->name}", 'landscape');
    }

    /**
     * Preview Laporan Usia Tahunan (inline)
     */
    public function previewYearly(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'class_id' => 'required',
            'year'     => 'required|integer',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::find($request->class_id);
        if (!$class) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher;
        }

        $school = SchoolProfile::first();
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        $startYear = (int) $request->year;
        $academicYear = "{$startYear}/".($startYear + 1);

        $academicMonths = [
            ['m' => 7,  'y' => $startYear],
            ['m' => 8,  'y' => $startYear],
            ['m' => 9,  'y' => $startYear],
            ['m' => 10, 'y' => $startYear],
            ['m' => 11, 'y' => $startYear],
            ['m' => 12, 'y' => $startYear],
            ['m' => 1,  'y' => $startYear + 1],
            ['m' => 2,  'y' => $startYear + 1],
            ['m' => 3,  'y' => $startYear + 1],
            ['m' => 4,  'y' => $startYear + 1],
            ['m' => 5,  'y' => $startYear + 1],
            ['m' => 6,  'y' => $startYear + 1],
        ];

        $students = Student::where('class_id', $request->class_id)
            ->get()
            ->makeHidden(['photo_url']);

        $ageRanges = range(6, 14);
        $monthlyData = [];

        foreach ($academicMonths as $item) {
            $targetDate = Carbon::createFromDate($item['y'], $item['m'], 1)->endOfMonth();

            $matrix = [
                'L' => array_fill_keys($ageRanges, 0),
                'P' => array_fill_keys($ageRanges, 0),
            ];

            foreach ($students as $student) {
                if (!empty($student->dob)) {
                    try {
                        $birthDate = $student->dob instanceof Carbon
                            ? $student->dob
                            : Carbon::parse($student->dob);

                        if ($birthDate->greaterThanOrEqualTo($targetDate)) {
                            continue;
                        }

                        $age = (int) $birthDate->diffInYears($targetDate);
                        $gender = strtoupper(trim($student->gender ?? ''));

                        if (in_array($gender, ['L', 'P']) && isset($matrix[$gender][$age])) {
                            $matrix[$gender][$age]++;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            }

            $monthlyData[] = [
                'month_name' => $targetDate->translatedFormat('F Y'),
                'matrix'     => $matrix,
            ];
        }

        $lastMonthDate = Carbon::createFromDate($startYear + 1, 6, 1)->endOfMonth();

        return $this->previewPdf('pdf.laporan-usia-tahunan', [
            'class'        => $class,
            'school'       => $school,
            'headmaster'   => $headmaster,
            'teacher'      => $teacher,
            'academicYear' => $academicYear,
            'monthlyData'  => $monthlyData,
            'ageRanges'    => $ageRanges,
            'titiMangsa'   => $lastMonthDate->translatedFormat('d F Y'),
        ], "Laporan_Usia_Tahunan_{$class->name}", 'landscape');
    }
}