<?php

namespace App\Filament\Admin\Resources\AcadAcademicYears\Pages;

use App\Filament\Admin\Resources\AcadAcademicYears\AcadAcademicYearResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAcadAcademicYear extends EditRecord
{
    protected static string $resource = AcadAcademicYearResource::class;

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
