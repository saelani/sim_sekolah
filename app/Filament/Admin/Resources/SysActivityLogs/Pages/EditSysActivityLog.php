<?php

namespace App\Filament\Admin\Resources\SysActivityLogs\Pages;

use App\Filament\Admin\Resources\SysActivityLogs\SysActivityLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSysActivityLog extends EditRecord
{
    protected static string $resource = SysActivityLogResource::class;

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
