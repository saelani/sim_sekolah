<?php

namespace App\Filament\Admin\Resources\SysActivityLogs\Pages;

use App\Filament\Admin\Resources\SysActivityLogs\SysActivityLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSysActivityLog extends CreateRecord
{
    protected static string $resource = SysActivityLogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
