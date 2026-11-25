<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\OpsStudentSavings;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentSavingPdfController extends Controller
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
                abort(403, 'Anda tidak memiliki hak akses untuk mencetak atau melihat laporan tabungan kelas ini.');
            }
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return $teacher;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:acad_classes,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

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

        $school = SchoolProfile::first() ?? new SchoolProfile();

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::parse($request->end_date)->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        $students = Student::where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get();

        $summary = $students->map(function ($student) use ($request) {
            $prevSavings = OpsStudentSavings::where('student_id', $student->id)
                ->where('date', '<', $request->start_date);
            $prevIn  = (clone $prevSavings)->where('type', 'in')->sum('amount');
            $prevOut = (clone $prevSavings)->where('type', 'out')->sum('amount');
            $saldoLalu = $prevIn - $prevOut;

            $currentSavings = OpsStudentSavings::where('student_id', $student->id)
                ->whereBetween('date', [$request->start_date, $request->end_date]);
            $setorSekarang = $currentSavings->where('type', 'in')->sum('amount');

            $totalSekarang = $saldoLalu + $setorSekarang;

            return [
                'nisn'           => $student->nis ?? '-',
                'name'           => $student->name,
                'saldo_lalu'     => (float) $saldoLalu,
                'setor_sekarang' => (float) $setorSekarang,
                'total_sekarang' => (float) $totalSekarang,
            ];
        });

        $pdf = Pdf::loadView('pdf.laporan-tabungan-siswa', [
            'class'              => $class,
            'summary'            => $summary,
            'startDate'          => $request->start_date,
            'endDate'            => $request->end_date,
            'school'             => $school,
            'headmaster'         => $headmaster,
            'teacher'            => $teacher,
            'titiMangsa'         => $titiMangsa,
            'grandTotalLalu'     => $summary->sum('saldo_lalu'),
            'grandSetorSekarang' => $summary->sum('setor_sekarang'),
            'grandTotalSekarang' => $summary->sum('total_sekarang'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan_Tabungan_Kelas_{$class->name}.pdf");
    }

    /**
     * Preview PDF di browser (inline) — sebelum print
     */
    public function preview(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:acad_classes,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

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

        $school = SchoolProfile::first() ?? new SchoolProfile();

        $city = $school->city ?? $school->district ?? 'Sekolah';
        $formattedDate = Carbon::parse($request->end_date)->translatedFormat('d F Y');
        $titiMangsa = "{$city}, {$formattedDate}";

        $students = Student::where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get();

        $summary = $students->map(function ($student) use ($request) {
            $prevSavings = OpsStudentSavings::where('student_id', $student->id)
                ->where('date', '<', $request->start_date);
            $prevIn  = (clone $prevSavings)->where('type', 'in')->sum('amount');
            $prevOut = (clone $prevSavings)->where('type', 'out')->sum('amount');
            $saldoLalu = $prevIn - $prevOut;

            $currentSavings = OpsStudentSavings::where('student_id', $student->id)
                ->whereBetween('date', [$request->start_date, $request->end_date]);
            $setorSekarang = $currentSavings->where('type', 'in')->sum('amount');

            $totalSekarang = $saldoLalu + $setorSekarang;

            return [
                'nisn'           => $student->nis ?? '-',
                'name'           => $student->name,
                'saldo_lalu'     => (float) $saldoLalu,
                'setor_sekarang' => (float) $setorSekarang,
                'total_sekarang' => (float) $totalSekarang,
            ];
        });

        return $this->previewPdf('pdf.laporan-tabungan-siswa', [
            'class'              => $class,
            'summary'            => $summary,
            'startDate'          => $request->start_date,
            'endDate'            => $request->end_date,
            'school'             => $school,
            'headmaster'         => $headmaster,
            'teacher'            => $teacher,
            'titiMangsa'         => $titiMangsa,
            'grandTotalLalu'     => $summary->sum('saldo_lalu'),
            'grandSetorSekarang' => $summary->sum('setor_sekarang'),
            'grandTotalSekarang' => $summary->sum('total_sekarang'),
        ], "Laporan_Tabungan_Kelas_{$class->name}", 'landscape');
    }
}