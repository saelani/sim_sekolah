<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\FormatifResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormatif extends CreateRecord
{
    protected static string $resource = FormatifResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
