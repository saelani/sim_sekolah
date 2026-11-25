<?php

namespace App\Filament\Admin\Resources\SysActivityLogs\Pages;

use App\Filament\Admin\Resources\SysActivityLogs\SysActivityLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSysActivityLogs extends ListRecords
{
    protected static string $resource = SysActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
