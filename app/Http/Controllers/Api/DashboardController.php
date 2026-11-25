<?php

namespace App\Http\Controllers\Api;

use App\Models\SchoolProfile;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $profile = SchoolProfile::first();

        return $this->success([
            'profile' => $profile ? $this->profileResource($profile) : null,
            'stats' => [
                'students' => \App\Models\Student::count(),
                'teachers' => \App\Models\Teacher::count(),
                'classes' => \App\Models\SchoolClass::count(),
                'subjects' => \App\Models\Subject::count(),
            ],
        ]);
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
        ];
    }
}
