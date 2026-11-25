<?php

namespace App\Filament\Admin\Resources\TeacherSubjectClasses\Pages;

use App\Filament\Admin\Resources\TeacherSubjectClasses\TeacherSubjectClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacherSubjectClass extends EditRecord
{
    protected static string $resource = TeacherSubjectClassResource::class;

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
