<?php

namespace App\Filament\Admin\Resources\SchoolProfiles\Pages;

use App\Filament\Admin\Resources\SchoolProfiles\SchoolProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolProfile extends EditRecord
{
    protected static string $resource = SchoolProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
