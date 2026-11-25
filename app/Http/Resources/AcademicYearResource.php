<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademicYearResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'year'       => $this->year,
            'semester'   => $this->semester,
            'is_active'  => (bool) $this->is_active,
        ];
    }
}
