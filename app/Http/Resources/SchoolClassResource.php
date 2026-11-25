<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'phase'           => $this->phase,
            'level'           => $this->level,
            'academic_year'   => $this->academicYear ? [
                'id'    => $this->academicYear->id,
                'year'  => $this->academicYear->year,
                'semester' => $this->academicYear->semester,
            ] : null,
            'homeroom_teacher'=> $this->homeroomTeacher ? [
                'id'    => $this->homeroomTeacher->id,
                'name'  => $this->homeroomTeacher->name,
                'nip'   => $this->homeroomTeacher->nip,
            ] : null,
            'students_count'  => \App\Models\Student::where('class_id', $this->id)->count(),
        ];
    }
}
