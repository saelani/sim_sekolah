<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    /**
     * Menampilkan halaman login siswa (berdiri sendiri, tanpa menu web).
     */
    public function showLoginForm(): View
    {
        return view('student.login');
    }

    /**
     * Proses login siswa.
     * Diterima email/ NIS, dicek ke tabel sys_users, lalu redirect ke dashboard siswa.
     */
    public function login(Request $request)
    {
        $email = trim($request->input('email'));
        $password = $request->input('password');

        // Debug: show what's being tried
        \Log::info('Login attempt', [
            'email' => $email,
        ]);

        $user = User::where('email', $email)->first();

        // Fallback: kalau email tidak ketemu, coba dari NIS
        if (! $user) {
            \Log::info('Email not found, trying NIS lookup');
            $student = Student::where('nis', $email)->first();
            if ($student) {
                $user = User::find($student->user_id);
            }
        }

        if ($user) {
            \Log::info('User found', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            if ($user->role === 'student') {
                $student = Student::where('user_id', $user->id)->first();
                $nis = $student ? $student->nis : '0000';

                return redirect()->route('student.dashboard', ['nis' => $nis])
                    ->with('status', 'Login berhasil, ' . $user->name . '.');
            }

            return redirect('/admin')->with('status', 'Login berhasil.');
        }

        \Log::info('Login Failed', [
            'email' => $email,
            'user_found' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'role' => $user ? $user->role : null,
        ]);

        return back()->withInput()->with('error', 'Email/NIS atau password salah.');
    }

    /**
     * Logout siswa.
     * Setelah logout, dialihkan ke halaman beranda/dashboard sekolah.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.home')
            ->with('status', 'Anda telah logout.');
    }
}
