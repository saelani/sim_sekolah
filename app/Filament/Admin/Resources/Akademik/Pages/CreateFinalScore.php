<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\FinalScoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinalScore extends CreateRecord
{
    protected static string $resource = FinalScoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
