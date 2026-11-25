<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars\Pages;

use App\Filament\Admin\Resources\GradeExtracurriculars\GradeExtracurricularResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGradeExtracurricular extends EditRecord
{
    protected static string $resource = GradeExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
