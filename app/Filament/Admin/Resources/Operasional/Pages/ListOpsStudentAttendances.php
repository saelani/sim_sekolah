<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsStudentAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListOpsStudentAttendances extends ListRecords
{
    protected static string $resource = OpsStudentAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('downloadMonthlyPdf')
                ->label('Download Laporan Bulanan PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('class_id')
                        ->label('Kelas')
                        ->options(\App\Models\SchoolClass::pluck('name', 'id'))
                        ->required(),
                    \Filament\Forms\Components\Select::make('month')
                        ->label('Bulan')
                        ->options([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ])
                        ->default(now()->month)
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('student-attendance.monthly-pdf', [
                        'class_id' => $data['class_id'],
                        'month' => $data['month'],
                        'year' => $data['year'],
                    ]);

                    // return redirect()->away($url);
                    $this->js("window.open('{$url}', '_blank');");
                }),

            Action::make('bulkAttendance')
                ->label('Input Kehadiran Massal')
                ->url(OpsStudentAttendanceResource::getUrl('bulk'))
                ->color('success')
                ->icon('heroicon-o-users'),
        ];
    }
}