<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/siswa/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if the user is a student
        if ($user->role !== 'student') {
            return redirect('/admin')->with('error', 'Akses ditolak. Anda bukan siswa.');
        }

        // Check if the student exists and has a valid NIS
        $nis = $request->route('nis');
        if (! $nis) {
            return redirect('/admin')->with('error', 'NIS tidak ditemukan.');
        }

        $student = \App\Models\Student::where('nis', $nis)->first();
        if (! $student) {
            return redirect('/admin/login')->with('error', 'Siswa dengan NIS tersebut tidak ditemukan.');
        }

        // Ensure the logged-in user is the student themselves
        if ($user->id !== $student->user_id) {
            return redirect('/admin/login')->with('error', 'Anda tidak berhak mengakses halaman ini.');
        }

        return $next($request);
    }
}
