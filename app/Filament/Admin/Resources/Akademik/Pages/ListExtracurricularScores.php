<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\ExtracurricularScoreResource;
use Filament\Actions\CreateAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtracurricularScores extends ListRecords
{
    protected static string $resource = ExtracurricularScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            // Tombol kustom untuk menuju ke halaman Input Massal
            Actions\Action::make('bulkCreate')
                ->label('Input Nilai Massal')
                ->url(ExtracurricularScoreResource::getUrl('bulk-create')) // Mengarah ke route 'bulk-create' yang sudah didaftarkan
                ->color('success') // Warna tombol (bisa success, primary, warning, info, dll)
                ->icon('heroicon-o-document-plus'), // Icon pendukung
        ];
    }
}
