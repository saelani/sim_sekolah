<?php

namespace App\Filament\Admin\Resources\StudentSavings\Pages;

use App\Filament\Admin\Resources\StudentSavings\StudentSavingResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class ListStudentSavings extends ListRecords
{
    protected static string $resource = StudentSavingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('batchSaving')
                ->label('Input Tabungan Massal')
                ->url(StudentSavingResource::getUrl('bulk'))
                ->color('success')
                ->icon('heroicon-o-users'),

            Action::make('printSavingsPdf')
                ->label('Cetak Laporan Tabungan')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Select::make('class_id')
                        ->label('Kelas')
                        ->options(\App\Models\SchoolClass::pluck('name', 'id'))
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->default(now()->startOfMonth())
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->default(now()->endOfMonth())
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Mengarahkan ke route controller PDF tabungan
                    return redirect()->route('pdf.student-saving.report', [
                        'class_id'   => $data['class_id'],
                        'start_date' => $data['start_date'],
                        'end_date'   => $data['end_date'],
                    ]);
                }),
        ];
    }
}
