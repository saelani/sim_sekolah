<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentSavings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHomeController extends Controller
{
    /**
     * Halaman beranda siswa (mini dashboard pilihan menu).
     * Halaman terpisah dari dashboard (siswa/{nis}), berisi pilihan menu:
     * Tabungan, Kehadiran, Laporan.
     */
    public function index($nis)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'student') {
            return redirect('/admin')->with('error', 'Akses ditolak. Anda bukan siswa.');
        }

        $student = Student::where('nis', $nis)->first();

        if (! $student) {
            return redirect('/admin/login')->with('error', 'Siswa dengan NIS tersebut tidak ditemukan.');
        }

        if ($user->id !== $student->user_id) {
            return redirect('/admin/login')->with('error', 'Anda tidak berhak mengakses halaman ini.');
        }

        $savings = StudentSavings::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        $totalSavings = $savings->sum('amount');

        $attendances = StudentAttendance::where('student_id', $student->id)->get();

        $totalAttendance = $attendances->count();
        $hadir = $attendances->where('status', 'Hadir')->count();
        $sakit = $attendances->where('status', 'Sakit')->count();
        $izin = $attendances->where('status', 'Izin')->count();
        $alpa = $attendances->where('status', 'Alpa')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($hadir / $totalAttendance) * 100) : 0;

        // Jadwal pelajaran (merujuk ke model Schedule)
        $schedules = \App\Models\Schedule::where('class_id', $student->class_id)
            ->orderByRaw("FIELD(day_name, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get();

        $classes = $student->schoolClass;

        // Ulangan / CBT exam - ujian yang tersedia untuk kelas ini
        $exams = \App\Models\Exam::where('is_active', 1)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where(function ($q) use ($student) {
                $q->where('class_room_id', $student->class_id)
                  ->orWhereNull('class_room_id');
            })
            ->orderBy('start_time')
            ->get();

        return view(
            'student.home',
            compact(
                'student',
                'savings',
                'totalSavings',
                'attendances',
                'totalAttendance',
                'hadir',
                'sakit',
                'izin',
                'alpa',
                'attendanceRate',
                'classes',
                'schedules',
                'exams'
            )
        );
    }
}
