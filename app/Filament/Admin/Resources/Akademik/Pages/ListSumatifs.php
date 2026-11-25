<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumatifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\Akademik\Pages\BulkSumatifByScope;


class ListSumatifs extends ListRecords
{
    protected static string $resource = SumatifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            // Tombol kustom untuk menuju ke Halaman Input Massal Excel
            Action::make('bulkScore')
                ->label('Input Nilai Massal (Excel)')
                ->url(SumatifResource::getUrl('bulk-score'))
                // ->url(BulkSumativeScore::getUrl()) // Mengambil URL dari page kustom yang sudah didaftarkan
                ->color('success') // Warna tombol (success = hijau ala excel)
                ->icon('heroicon-o-table-cells'), // Ikon tabel

            Action::make('bulkInputByScope')
                ->label('Input Massal Sumatif')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success')
                ->url(SumatifResource::getUrl('bulk-by-scope')),
                // ->url(BulkSumatifByScope::getUrl()),
        ];
    }
}
