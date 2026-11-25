<?php

namespace App\Filament\Admin\Resources\TeacherSubjectClasses\Pages;

use App\Filament\Admin\Resources\TeacherSubjectClasses\TeacherSubjectClassResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherSubjectClass extends CreateRecord
{
    protected static string $resource = TeacherSubjectClassResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
