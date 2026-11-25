<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\ReportCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReportCard extends CreateRecord
{
    protected static string $resource = ReportCardResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
