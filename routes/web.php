<?php

use App\Http\Controllers\AdminAuth\AdminLoginController;
use App\Http\Controllers\Pdf\AcademicCalendarPdfController;
use App\Http\Controllers\Pdf\AcademicCalendarWeeklyPdfController;
use App\Http\Controllers\Pdf\LearningObjectiveReportController;
use App\Http\Controllers\Pdf\SchedulePdfController;
use App\Http\Controllers\Pdf\StudentAgePdfController;
use App\Http\Controllers\Pdf\StudentAttendancePdfController;
use App\Http\Controllers\Pdf\StudentCompleteReportController;
use App\Http\Controllers\Pdf\StudentSavingPdfController;
use App\Http\Controllers\Pdf\SumativeScopeReportController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\StudentHomeController;
use App\Http\Controllers\StudentRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* Storage route (untuk logo/report PDF) */
Route::get('/storage/{path}', function ($path) {
    $decoded = rawurldecode($path);

    // 1) Blokir path traversal (../, ..\)
    if (str_contains($decoded, '..') || str_contains($decoded, '\\')) {
        abort(403);
    }

    // 2) Pastikan file benar-benar di dalam storage/app/public
    $base = realpath(storage_path('app/public'));
    $filePath = realpath(storage_path('app/public/' . $decoded));
    if ($filePath === false || !is_file($filePath) || !str_starts_with($filePath, $base . '/')) {
        abort(404);
    }

    // 3) Proteksi akses: foto profil siswa & staf tidak boleh diakses tanpa sesi login
    if (str_starts_with($decoded, 'student/')) {
        if (! Auth::check()) {
            abort(403, 'Akses ditolak.');
        }
    }

    $type = mime_content_type($filePath);

    return response(file_get_contents($filePath), 200)
        ->header('Content-Type', $type);
})->where('path', '.*');

/* PDF Routes (Protected by Auth) */
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin/pdf')->name('pdf.student-saving.')->group(function () {
        Route::get('/student-saving-report', StudentSavingPdfController::class)->name('report');
    });
    Route::get('/student-complete-report/pdf', [StudentCompleteReportController::class, 'generate'])
        ->name('student-complete-report.pdf');
    Route::get('/student-age/monthly-pdf', [StudentAgePdfController::class, 'monthlyPdf'])
        ->name('student-age.monthly-pdf');
    Route::get('/student-age/yearly-pdf', [StudentAgePdfController::class, 'yearlyPdf'])
        ->name('student-age.yearly-pdf');
    Route::get('/learning-objective/pdf', [LearningObjectiveReportController::class, 'generatePdf'])->name('learning-objective.pdf');
    Route::get('/pdf/schedules', SchedulePdfController::class)->name('pdf.schedules');
    Route::get('/academic-calendar/pdf', AcademicCalendarPdfController::class)->name('academic-calendar.pdf');
    Route::get('/student-saving/pdf', StudentSavingPdfController::class)->name('student-saving.pdf');
    Route::get('/student-attendance/monthly-pdf', [StudentAttendancePdfController::class, 'monthlyPdf'])->name('student-attendance.monthly-pdf');
    Route::get('/student-attendance/pdf', StudentAttendancePdfController::class)->name('student-attendance.pdf');
    Route::get('/student-age/preview', [StudentAgePdfController::class, 'preview'])->name('student-age.preview');
    Route::get('/student-age/yearly-preview', [StudentAgePdfController::class, 'previewYearly'])->name('student-age.yearly-preview');
    Route::get('/learning-objective/preview', [LearningObjectiveReportController::class, 'preview'])->name('learning-objective.preview');
    Route::get('/pdf/schedules/preview', [SchedulePdfController::class, 'preview'])->name('pdf.schedules.preview');
    Route::get('/academic-calendar/preview', [AcademicCalendarPdfController::class, 'preview'])->name('academic-calendar.preview');
    Route::get('/student-saving/preview', [StudentSavingPdfController::class, 'preview'])->name('student-saving.preview');
    Route::get('/student-attendance/preview', [StudentAttendancePdfController::class, 'preview'])->name('student-attendance.preview');
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('/sumative-scope/preview', [SumativeScopeReportController::class, 'preview'])->name('sumative-scope.preview');
        Route::get('/sumative-scope/generate', [SumativeScopeReportController::class, 'generatePdf'])->name('sumative-scope.generate');
    });
    Route::get('/academic-calendar/weekly-pdf', [AcademicCalendarWeeklyPdfController::class, 'generatePdf'])->name('academic-calendar.weekly-pdf');
    Route::get('/academic-calendar/weekly-preview', [AcademicCalendarWeeklyPdfController::class, 'preview'])->name('academic-calendar.weekly-preview');
});

/* Web Routes - Public / Frontend */
Route::get('/berita/{post:slug}', function ($post) {
    return view('berita.show', ['post' => $post]);
})->name('berita.show');

/* Public / Frontend Routes */
Route::get('/', [PublicWebsiteController::class, 'home'])->name('public.home');
Route::get('/public/tentang', [PublicWebsiteController::class, 'about'])->name('public.about');
Route::get('/public/guru', [PublicWebsiteController::class, 'teachers'])->name('public.teachers');
Route::get('/public/guru/{id}', [PublicWebsiteController::class, 'teacher'])->name('public.teacher');
Route::get('/public/kelas', [PublicWebsiteController::class, 'classes'])->name('public.classes');
Route::get('/public/mapel', [PublicWebsiteController::class, 'subjects'])->name('public.subjects');
Route::get('/public/data', [PublicWebsiteController::class, 'index'])->name('public.data');

/* Berita & Galeri */
Route::get('/public/berita', [PublicWebsiteController::class, 'news'])->name('public.news');
Route::get('/public/berita/{slug}', [PublicWebsiteController::class, 'news_show'])->name('public.news.show');
Route::get('/public/galeri', [PublicWebsiteController::class, 'gallery'])->name('public.gallery');
Route::get('/public/galeri/{slug}', [PublicWebsiteController::class, 'gallery_show'])->name('public.gallery.show');

/* Auth / Login Routes */
Route::get('/login', function () {
    $profile = \App\Models\SchoolProfile::first();
    return view('admin.login', compact('profile'));
})->name('login');

/* Admin Panel Auth Routes */
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])
    ->name('filament.admin.auth.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('filament.admin.auth.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])
    ->name('filament.admin.auth.logout');

/* Student Auth Routes (harus di atas /siswa/{nis}) */
Route::prefix('siswa')->name('student.')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});

/* Student Area & CBT Exam Routes */
Route::prefix('siswa/{nis}')
    ->middleware('student.auth')
    ->name('student.')
    ->group(function () {
        Route::get('/', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/beranda', [StudentHomeController::class, 'index'])->name('home');

        /* Ujian / CBT */
        Route::get('/ujian', [StudentExamController::class, 'index'])->name('exam');
        Route::post('/ujian/start', [StudentExamController::class, 'start'])->name('exam.start');
        Route::get('/ujian/{token}/{id}', [StudentExamController::class, 'take'])->name('exam.take');
        Route::post('/ujian/{token}/{id}/submit', [StudentExamController::class, 'submit'])->name('exam.submit');

        /* Permohonan (student-facing request forms) */
        Route::get('/absen/minta', [StudentRequestController::class, 'attendance'])->name('attendance-request');
        Route::post('/absen/minta', [StudentRequestController::class, 'attendanceStore'])->name('attendance-request.store');
        Route::get('/tabungan/minta', [StudentRequestController::class, 'savings'])->name('savings-request');
        Route::post('/tabungan/minta', [StudentRequestController::class, 'savingsStore'])->name('savings-request.store');
    });