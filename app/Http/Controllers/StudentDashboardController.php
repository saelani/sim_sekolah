<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Formatif;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Sumatif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StudentDashboardController extends Controller
{
    /**
     * Halaman dashboard siswa.
     * Hanya bisa diakses oleh siswa yang login.
     */
    public function dashboard($nis)
    {
        $user = Auth::user();

        // Pastikan user login
        if (! $user) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan user adalah siswa
        if ($user->role !== 'student') {
            return redirect('/admin')->with('error', 'Akses ditolak. Anda bukan siswa.');
        }

        // Cari student berdasarkan NIS
        $student = Student::where('nis', $nis)->first();

        if (! $student) {
            return redirect('/admin/login')->with('error', 'Siswa dengan NIS tersebut tidak ditemukan.');
        }

        // Pastikan user yang login adalah siswa yang bersangkutan
        if ($user->id !== $student->user_id) {
            return redirect('/admin/login')->with('error', 'Anda tidak berhak mengakses halaman ini.');
        }

        // Ambil data tabungan (savings) - sesuai dengan model StudentSavings
        $savings = \App\Models\StudentSavings::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $totalSavings = $savings->sum('amount');

        // Ambil data kehadiran (attendance) - sesuai dengan model StudentAttendance
        $attendances = \App\Models\StudentAttendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        // Jadwal pelajaran - merujuk ke model Schedule
        $schedules = \App\Models\Schedule::where('class_id', $student->class_id)
            ->orderByRaw("FIELD(day_name, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get();

        $classes = SchoolClass::find($student->class_id);

        $exams = \App\Models\Exam::where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where(function ($q) use ($student) {
                $q->where('class_room_id', $student->class_id)
                    ->orWhereNull('class_room_id');
            })
            ->orderBy('start_time')
            ->get();

        // Materi & modul - sesuai dengan model Material
        $materials = \App\Models\Material::where('class_id', $student->class_id)
            ->where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil ID enrollment siswa untuk nilai formatif & sumatif
        $enrollmentIds = collect();
        if (method_exists($student, 'enrollments')) {
            $enrollmentIds = $student->enrollments()->pluck('id');
        } elseif (class_exists(\App\Models\Enrollment::class)) {
            $enrollmentModel = new \App\Models\Enrollment();
            $enrollmentTable = $enrollmentModel->getTable();
            if (Schema::hasColumn($enrollmentTable, 'student_id')) {
                $enrollmentIds = \App\Models\Enrollment::where('student_id', $student->id)->pluck('id');
            }
        }

        // 1. Nilai Formatif (grade_formatifs)
        $formatives = collect();
        if ($enrollmentIds->isNotEmpty()) {
            $formatives = Formatif::with(['learningObjective.subject'])
                ->whereIn('enrollment_id', $enrollmentIds)
                ->latest()
                ->get();
        }

        // 2. Nilai Sumatif (grade_sumatifs)
        $sumatives = collect();
        if ($enrollmentIds->isNotEmpty()) {
            $sumatives = Sumatif::with(['subject', 'sumativeScope'])
                ->whereIn('enrollment_id', $enrollmentIds)
                ->latest()
                ->get();
        }

        // 3. Sesi Ujian CBT (cbt_exam_sessions)
        $examSessions = ExamSession::with(['exam'])
            ->where(function ($q) use ($student, $enrollmentIds) {
                $q->where('student_id', $student->id);
                if ($enrollmentIds->isNotEmpty()) {
                    $q->orWhereIn('enrollment_id', $enrollmentIds);
                }
            })
            ->latest()
            ->get();

        // 4. Nilai Akhir (FinalScore - opsional bila model tersedia)
        $finalscores = collect();
        if (class_exists(\App\Models\FinalScore::class)) {
            $finalModel = new \App\Models\FinalScore();
            $finalTable = $finalModel->getTable();
            $finalQuery = \App\Models\FinalScore::query();

            // if (method_exists($finalModel, 'subject')) {
            //     $finalQuery->with('subject');
            // }

            if (method_exists($finalModel, 'subject')) {
                $finalQuery->with('subject');
            }
            
            if (method_exists($finalModel, 'enrollment')) {
                $finalQuery->with('enrollment.subject');
            }

            if (Schema::hasColumn($finalTable, 'student_id') && $enrollmentIds->isNotEmpty()) {
                $finalQuery->where(function ($q) use ($student, $enrollmentIds) {
                    $q->where('student_id', $student->id)
                        ->orWhereIn('enrollment_id', $enrollmentIds);
                });
            } elseif (Schema::hasColumn($finalTable, 'student_id')) {
                $finalQuery->where('student_id', $student->id);
            } elseif (Schema::hasColumn($finalTable, 'enrollment_id') && $enrollmentIds->isNotEmpty()) {
                $finalQuery->whereIn('enrollment_id', $enrollmentIds);
            }

            $finalscores = $finalQuery->latest()->get();
        }

        // 5. Nilai Ekstrakurikuler (ExtrakurikulerScore - opsional bila model tersedia)
        $extrakurikulerscores = collect();
        $ekskulModelClass = class_exists(\App\Models\ExtrakurikulerScore::class)
            ? \App\Models\ExtrakurikulerScore::class
            : (class_exists(\App\Models\ExtracurricularScore::class) ? \App\Models\ExtracurricularScore::class : null);

        if ($ekskulModelClass) {
            $ekskulModel = new $ekskulModelClass();
            $ekskulTable = $ekskulModel->getTable();
            $ekskulQuery = $ekskulModelClass::query();

            if (method_exists($ekskulModel, 'extracurricular')) {
                $ekskulQuery->with('extracurricular');
            }
            if (method_exists($ekskulModel, 'extrakurikuler')) {
                $ekskulQuery->with('extrakurikuler');
            }

            if (Schema::hasColumn($ekskulTable, 'student_id')) {
                $ekskulQuery->where('student_id', $student->id);
            } elseif (Schema::hasColumn($ekskulTable, 'enrollment_id') && $enrollmentIds->isNotEmpty()) {
                $ekskulQuery->whereIn('enrollment_id', $enrollmentIds);
            }

            $extrakurikulerscores = $ekskulQuery->latest()->get();
        }

        return view('student.dashboard', compact(
            'student',
            'savings',
            'totalSavings',
            'attendances',
            'classes',
            'schedules',
            'exams',
            'materials',
            'formatives',
            'sumatives',
            'examSessions',
            'finalscores',
            'extrakurikulerscores'
        ));
    }
}