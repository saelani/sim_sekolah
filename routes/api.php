<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SchoolProfileController;
use App\Http\Controllers\Api\AcademicYearController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\SchoolClassController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them are
| protected by the "auth:sanctum" middleware group. A sample
| endpoint is provided for a welcome check.
*/

Route::get('/up', function () {
    return response()->json(['status' => 'ok', 'service' => config('app.name')], 200);
});

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile
    Route::get('/profile', [SchoolProfileController::class, 'show']);

    // Academic
    Route::get('/academic-years', [AcademicYearController::class, 'index']);
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/classes', [SchoolClassController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/students', [StudentController::class, 'index']);

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
});
