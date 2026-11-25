<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'nip'          => $this->nip,
            'nuptk'        => $this->nuptk,
            'name'         => $this->name,
            'front_title'  => $this->front_title,
            'back_title'   => $this->back_title,
            'gender'       => $this->gender,
            'role_type'    => $this->role_type,
            'is_active'    => (bool) $this->is_active,
            'photo_path'   => $this->photo_path,
            'user'         => $this->user ? [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
                'role'  => $this->user->role,
            ] : null,
            'classes'      => $this->teacherSubjectClass->map(function ($tsc) {
                return [
                    'class_id'   => $tsc->schoolClass ? $tsc->schoolClass->id : null,
                    'class_name' => $tsc->schoolClass ? $tsc->schoolClass->name : null,
                    'subject'    => $tsc->subject ? $tsc->subject->name : null,
                ];
            }),
        ];
    }
}
