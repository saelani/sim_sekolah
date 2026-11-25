<?php

namespace App\Filament\Admin\Resources\TeacherSubjectClasses\Pages;

use App\Filament\Admin\Resources\TeacherSubjectClasses\TeacherSubjectClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeacherSubjectClasses extends ListRecords
{
    protected static string $resource = TeacherSubjectClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
