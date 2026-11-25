<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\FormatifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Akademik\Pages\BulkFormatifByTp;
use Filament\Actions\Action;

class ListFormatifs extends ListRecords
{
    protected static string $resource = FormatifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('bulkInputByTp')
                ->label('Input Massal per TP')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success')
                ->url(BulkFormatifByTp::getUrl()),
        ];
    }
}
