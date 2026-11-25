<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'code'        => $this->code,
            'name'        => $this->name,
            'category'    => $this->category,
            'order_number'=> $this->order_number,
            'classes'     => $this->teacherSubjectClasses->map(function ($tsc) {
                return [
                    'class_id'   => $tsc->class_id,
                    'class_name' => $tsc->schoolClass ? $tsc->schoolClass->name : '-',
                    'phase'      => $tsc->schoolClass ? $tsc->schoolClass->phase : '-',
                    'level'      => $tsc->schoolClass ? $tsc->schoolClass->level : '-',
                ];
            }),
        ];
    }
}
