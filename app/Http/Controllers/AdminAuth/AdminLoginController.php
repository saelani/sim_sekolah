<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function showLoginForm(): View
    {
        $profile = \App\Models\SchoolProfile::first();
        return view('admin.login', compact('profile'));
    }

    public function login(Request $request)
    {
        $email = trim($request->input('email'));
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);

            // Route berdasarkan role
            if ($user->role === 'student') {
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    return redirect()->route('student.dashboard', ['nis' => $student->nis])
                        ->with('status', 'Login berhasil, ' . $user->name . '.');
                }
                return redirect()->route('student.dashboard', ['nis' => '0000'])
                    ->with('status', 'Login berhasil, ' . $user->name . '.');
            }

            return redirect('/admin')->with('status', 'Login berhasil.');
        }

        return back()->withInput()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
