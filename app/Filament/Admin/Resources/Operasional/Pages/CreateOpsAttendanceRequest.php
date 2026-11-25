<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsAttendanceRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOpsAttendanceRequest extends CreateRecord
{
    protected static string $resource = OpsAttendanceRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
