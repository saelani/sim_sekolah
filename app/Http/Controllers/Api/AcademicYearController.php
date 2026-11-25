<?php

namespace App\Http\Controllers\Api;

use App\Models\AcademicYear;
use Illuminate\Http\JsonResponse;

class AcademicYearController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $years = AcademicYear::orderBy('id', 'desc')->get();

        return $this->success($years->map(function (AcademicYear $y) {
            return [
                'id' => $y->id,
                'year' => $y->year,
                'semester' => $y->semester,
                'is_active' => (bool) $y->is_active,
            ];
        })->toArray());
    }
}
