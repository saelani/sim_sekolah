<?php

namespace App\Http\Controllers\Api;

use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;

class SchoolClassController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $classes = SchoolClass::with('AcademicYear')->orderBy('academic_year_id', 'desc')
            ->orderBy('phase', 'asc')->orderBy('level', 'asc')->get();

        return $this->success($classes->map(function (SchoolClass $c) {
            return [
                'id' => $c->id,
                'academic_year_id' => $c->academic_year_id,
                'name' => $c->name,
                'phase' => $c->phase,
                'level' => $c->level,
                'teacher_id' => $c->teacher_id,
                'academic_year' => $c->AcademicYear ? [
                    'id' => $c->AcademicYear->id,
                    'year' => $c->AcademicYear->year,
                    'semester' => $c->AcademicYear->semester,
                ] : null,
            ];
        })->toArray());
    }
}
