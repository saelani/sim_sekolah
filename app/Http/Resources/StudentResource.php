<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'nis'        => $this->nis,
            'nisn'       => $this->nisn,
            'name'       => $this->name,
            'gender'     => $this->gender,
            'religion'   => $this->religion,
            'dob'        => $this->dob,
            'class'      => $this->schoolClass ? [
                'id'    => $this->schoolClass->id,
                'name'  => $this->schoolClass->name,
                'phase' => $this->schoolClass->phase,
                'level' => $this->schoolClass->level,
            ] : null,
        ];
    }
}
