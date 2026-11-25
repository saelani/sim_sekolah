<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumatifResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSumatif extends CreateRecord
{
    protected static string $resource = SumatifResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
