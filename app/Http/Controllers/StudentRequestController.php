<?php

namespace App\Http\Controllers;

use App\Models\OpsAttendanceRequest;
use App\Models\SavingsRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentRequestController extends Controller
{
    /**
     * Verifikasi bahwa user login adalah siswa dengan NIS yang diminta.
     */
    private function authorizeStudent(string $nis): ?Student
    {
        $user = Auth::user();
        $student = Student::where('nis', $nis)->first();

        if (! $user || ! $student || (int) $user->id !== (int) $student->user_id) {
            return null;
        }

        return $student;
    }

    // ==========================
    // Ops Student Attendance
    // ==========================

    public function attendance(Request $request, string $nis)
    {
        $student = $this->authorizeStudent($nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        return view('student.attendance-request', [
            'student' => $student,
            'classes' => $student->schoolClass,
        ]);
    }

    public function attendanceStore(Request $request, string $nis)
    {
        $student = $this->authorizeStudent($nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        $data = $request->validate([
            'date' => 'required|date',
            'status' => ['required', Rule::in(['Hadir', 'Sakit', 'Izin', 'Alpa'])],
            'notes' => 'nullable|string|max:255',
        ]);

        $data['class_id'] = $student->class_id;
        $data['student_id'] = $student->id;
        $data['user_id'] = $student->user_id;
        $data['teacher_id'] = null;
        $data['attachment_path'] = null;
        $data['approval_status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;

        DB::transaction(function () use ($data) {
            OpsAttendanceRequest::create($data);
        });

        return redirect()
            ->route('student.attendance-request', ['nis' => $student->nis])
            ->with('status', 'Permohonan kehadiran berhasil dikirim. Menunggu persetujuan admin.');
    }

    // ==========================
    // Ops Student Savings
    // ==========================

    public function savings(Request $request, string $nis)
    {
        $student = $this->authorizeStudent($nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        return view('student.savings-request', [
            'student' => $student,
            'classes' => $student->schoolClass,
        ]);
    }

    public function savingsStore(Request $request, string $nis)
    {
        $student = $this->authorizeStudent($nis);
        if (! $student) {
            return redirect('/siswa/login')->with('error', 'Akses ditolak.');
        }

        $data = $request->validate([
            'date' => 'required|date',
            'type' => ['required', Rule::in(['deposit', 'withdrawal'])],
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        $data['student_id'] = $student->id;
        $data['date'] = $data['date'];
        $data['type'] = $data['type'];
        $data['amount'] = (string) (float) $data['amount'];
        $data['notes'] = $data['notes'];
        $data['status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;

        DB::transaction(function () use ($data) {
            SavingsRequest::create($data);
        });

        return redirect()
            ->route('student.savings-request', ['nis' => $student->nis])
            ->with('status', 'Permohonan tabungan berhasil dikirim. Menunggu persetujuan admin.');
    }
}
