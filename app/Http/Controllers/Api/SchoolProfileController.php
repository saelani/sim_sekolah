<?php

namespace App\Http\Controllers\Api;

use App\Models\SchoolProfile;
use Illuminate\Http\JsonResponse;

class SchoolProfileController extends BaseApiController
{
    public function show(): JsonResponse
    {
        $profile = SchoolProfile::first();

        if (! $profile) {
            return $this->error('School profile not found.', 404);
        }

        return $this->success($this->profileResource($profile));
    }

    protected function profileResource(SchoolProfile $profile): array
    {
        return [
            'id' => $profile->id,
            'npsn' => $profile->npsn,
            'school_name' => $profile->school_name,
            'headmaster_name' => $profile->headmaster_name,
            'accreditation' => $profile->accreditation,
            'address' => $profile->address,
            'phone' => $profile->phone,
            'email' => $profile->email,
            'website' => $profile->website,
            'vision' => $profile->vision,
            'mission' => $profile->mission,
            'logo_path' => $profile->logo_path,
            'maps_embed' => $profile->maps_embed,
        ];
    }
}
