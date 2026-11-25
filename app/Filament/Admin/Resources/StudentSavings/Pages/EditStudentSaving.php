<?php

namespace App\Filament\Admin\Resources\StudentSavings\Pages;

use App\Filament\Admin\Resources\StudentSavings\StudentSavingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentSaving extends EditRecord
{
    protected static string $resource = StudentSavingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
