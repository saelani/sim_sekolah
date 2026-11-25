<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Filament\Admin\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('printMonthlyPdf')
                ->label('Rekap Usia Bulanan')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    // Wajib ada karena di halaman list siswa tidak terikat pada 1 kelas saja
                    Select::make('class_id')
                        ->label('Pilih Kelas')
                        // ->options(SchoolClass::pluck('name', 'id'))
                        ->options(\App\Models\SchoolClass::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('month')
                        ->label('Bulan')
                        ->options([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                            4 => 'April', 5 => 'Mei', 6 => 'Juni',
                            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ])
                        ->default(now()->month)
                        ->required(),

                    Select::make('year')
                        ->label('Tahun')
                        ->options(array_combine(range(now()->year - 5, now()->year + 1), range(now()->year - 5, now()->year + 1)))
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('student-age.monthly-pdf', [
                        'class_id' => $data['class_id'],
                        'month'    => $data['month'],
                        'year'     => $data['year'],
                    ]);

                    $this->js("window.open('{$url}', '_blank');");
                }),

            // Tombol Cetak Rekap Usia Tahunan
            Action::make('printYearlyPdf')
                ->label('Rekap Usia Tahunan')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->form([
                    Select::make('class_id')
                        ->label('Pilih Kelas')
                        // ->options(SchoolClass::pluck('name', 'id'))
                        ->options(\App\Models\SchoolClass::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('year')
                        ->label('Tahun Ajaran (Mulai Juli)')
                        ->options(array_combine(range(now()->year - 5, now()->year + 1), range(now()->year - 5, now()->year + 1)))
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('student-age.yearly-pdf', [
                        'class_id' => $data['class_id'],
                        'year'     => $data['year'],
                    ]);

                    $this->js("window.open('{$url}', '_blank');");
                }),
            
            Action::make('printCompleteReport')
                ->label('Cetak Laporan Lengkap Siswa')
                ->icon('heroicon-o-document-text')
                ->color('warning')
                ->form([
                    Select::make('class_id')
                        ->label('Pilih Kelas')
                        // ->options(SchoolClass::pluck('name', 'id'))
                        ->options(\App\Models\SchoolClass::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('student-complete-report.pdf', [
                        'class_id' => $data['class_id'],
                    ]);

                    $this->js("window.open('{$url}', '_blank');");
                })
        ];
    }
}
