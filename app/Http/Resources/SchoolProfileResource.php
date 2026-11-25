<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'npsn'            => $this->npsn,
            'school_name'     => $this->school_name,
            'headmaster_name' => $this->headmaster_name,
            'accreditation'   => $this->accreditation,
            'address'         => $this->address,
            'phone'           => $this->phone,
            'email'           => $this->email,
            'website'         => $this->website,
            'vision'          => $this->vision,
            'mission'         => $this->mission,
            'logo_path'       => $this->logo_path,
            'maps_embed'      => $this->maps_embed,
        ];
    }
}
