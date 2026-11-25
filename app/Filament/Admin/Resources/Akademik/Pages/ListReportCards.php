<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\ReportCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReportCards extends ListRecords
{
    protected static string $resource = ReportCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
