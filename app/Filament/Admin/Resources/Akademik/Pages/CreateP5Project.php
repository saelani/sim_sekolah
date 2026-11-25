<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\P5ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateP5Project extends CreateRecord
{
    protected static string $resource = P5ProjectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
