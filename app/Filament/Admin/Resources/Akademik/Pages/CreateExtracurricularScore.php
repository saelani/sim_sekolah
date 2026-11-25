<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\ExtracurricularScoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExtracurricularScore extends CreateRecord
{
    protected static string $resource = ExtracurricularScoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
