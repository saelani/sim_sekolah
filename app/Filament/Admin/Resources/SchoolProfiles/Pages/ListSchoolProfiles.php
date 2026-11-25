<?php

namespace App\Filament\Admin\Resources\SchoolProfiles\Pages;

use App\Filament\Admin\Resources\SchoolProfiles\SchoolProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchoolProfiles extends ListRecords
{
    protected static string $resource = SchoolProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
