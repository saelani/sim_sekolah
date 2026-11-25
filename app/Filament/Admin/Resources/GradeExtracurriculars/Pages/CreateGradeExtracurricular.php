<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars\Pages;

use App\Filament\Admin\Resources\GradeExtracurriculars\GradeExtracurricularResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGradeExtracurricular extends CreateRecord
{
    protected static string $resource = GradeExtracurricularResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
