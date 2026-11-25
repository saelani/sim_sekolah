<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsCashFlowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOpsCashFlow extends CreateRecord
{
    protected static string $resource = OpsCashFlowResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
