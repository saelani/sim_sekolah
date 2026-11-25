<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class SubjectController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $subjects = Subject::orderBy('order_number', 'asc')->orderBy('name', 'asc')->get();

        return $this->success($subjects->map(function (Subject $s) {
            return [
                'id' => $s->id,
                'code' => $s->code,
                'name' => $s->name,
                'category' => $s->category,
                'order_number' => $s->order_number,
            ];
        })->toArray());
    }
}
