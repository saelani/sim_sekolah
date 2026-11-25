<?php

namespace App\Filament\Admin\Resources\AcadAcademicYears\Pages;

use App\Filament\Admin\Resources\AcadAcademicYears\AcadAcademicYearResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcadAcademicYears extends ListRecords
{
    protected static string $resource = AcadAcademicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
