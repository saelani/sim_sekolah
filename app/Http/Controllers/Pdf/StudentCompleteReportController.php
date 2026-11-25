<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Traits\PdfPreviewable;
use App\Models\SchoolClass;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentCompleteReportController extends Controller
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
                abort(403, 'Anda tidak memiliki hak akses untuk mencetak atau melihat laporan data siswa kelas ini.');
            }
        } elseif (!$isAdminOrHeadmaster) {
            abort(403, 'Unauthorized. Anda tidak memiliki hak akses.');
        }

        return $teacher;
    }

    public function generate(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'class_id' => 'required|exists:acad_classes,id',
        ]);

        Carbon::setLocale('id');

        $class = SchoolClass::with('homeroomTeacher')->find($request->class_id);
        if (!$class) {
            return response()->json(['message' => 'Data Kelas tidak ditemukan.'], 404);
        }

        // 🛡️ Validasi Hak Akses
        $teacher = $this->validateClassAccess($class);
        if (!$teacher) {
            $teacher = $class->homeroomTeacher ?? null;
        }

        $school = SchoolProfile::first() ?? new SchoolProfile();
        
        $headmaster = Teacher::where('role_type', 'headmaster')->first();
        if ($headmaster instanceof \Illuminate\Support\Collection) {
            $headmaster = $headmaster->first();
        }

        // Ambil data siswa berdasarkan class_id
        $students = Student::where('class_id', $request->class_id)
            ->orderBy('name', 'asc')
            ->get()
            ->makeHidden(['photo_url']);

        // Hitung rekapitulasi jenis kelamin
        $totalL = $students->where('gender', 'L')->count();
        $totalP = $students->where('gender', 'P')->count();
        $totalJml = $students->count();

        $pdf = Pdf::loadView('pdf.laporan-data-siswa-lengkap', [
            'class'      => $class,
            'school'     => $school,
            'headmaster' => $headmaster,
            'teacher'    => $teacher,
            'students'   => $students,
            'totalL'     => $totalL,
            'totalP'     => $totalP,
            'totalJml'   => $totalJml,
            'titiMangsa' => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Laporan_Data_Siswa_{$class->name}.pdf");
    }
}