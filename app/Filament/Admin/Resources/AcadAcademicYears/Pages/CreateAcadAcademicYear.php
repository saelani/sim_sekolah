<?php

namespace App\Filament\Admin\Resources\AcadAcademicYears\Pages;

use App\Filament\Admin\Resources\AcadAcademicYears\AcadAcademicYearResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAcadAcademicYear extends CreateRecord
{
    protected static string $resource = AcadAcademicYearResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
