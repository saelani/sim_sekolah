<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars\Pages;

use App\Filament\Admin\Resources\GradeExtracurriculars\GradeExtracurricularResource;
use Filament\Actions\CreateAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGradeExtracurriculars extends ListRecords
{
    protected static string $resource = GradeExtracurricularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            
        ];
    }
}
