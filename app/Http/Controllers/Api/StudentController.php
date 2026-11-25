<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class StudentController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin', 'headmaster', 'teacher'], true)) {
            return $this->error('Akses ditolak.', 403);
        }

        $students = Student::with(['SchoolClass', 'User'])->with(['AcademicYear'])->orderBy('name', 'asc')->get();

        return $this->success($students->map(function (Student $s) {
            return [
                'id' => $s->id,
                'user_id' => $s->user_id,
                'nis' => $s->nis,
                'nisn' => $s->nisn,
                'name' => $s->name,
                'gender' => $s->gender,
                'religion' => $s->religion,
                'pob' => $s->pob,
                'dob' => $s->dob,
                'address' => $s->address,
                'parent_name' => $s->parent_name,
                'class_id' => $s->class_id,
                'class' => $s->SchoolClass ? [
                    'id' => $s->SchoolClass->id,
                    'name' => $s->SchoolClass->name,
                    'phase' => $s->SchoolClass->phase,
                    'level' => $s->SchoolClass->level,
                    'academic_year' => $s->SchoolClass->AcademicYear ? [
                        'id' => $s->SchoolClass->AcademicYear->id,
                        'year' => $s->SchoolClass->AcademicYear->year,
                        'semester' => $s->SchoolClass->AcademicYear->semester,
                    ] : null,
                ] : null,
                'user' => $s->User ? [
                    'id' => $s->User->id,
                    'name' => $s->User->name,
                    'email' => $s->User->email,
                    'role' => $s->User->role,
                ] : null,
            ];
        })->toArray());
    }
}
