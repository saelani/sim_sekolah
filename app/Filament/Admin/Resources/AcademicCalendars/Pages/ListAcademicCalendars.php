<?php

namespace App\Filament\Admin\Resources\AcademicCalendars\Pages;

use App\Filament\Admin\Resources\AcademicCalendars\AcademicCalendarResource;
use Filament\Actions\CreateAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use App\Models\SchoolClass;
use App\Models\AcademicCalendar;

class ListAcademicCalendars extends ListRecords
{
    protected static string $resource = AcademicCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            // Tombol Cetak Jadwal Mingguan
            Actions\Action::make('printWeeklyPdf')
                ->label('Cetak Jadwal Mingguan')
                ->icon('heroicon-o-calendar')
                ->color('info')
                ->form([
                    Forms\Components\Select::make('class_id')
                        ->label('Kelas')
                        ->options(function () {
                            // Hanya tampilkan kelas yang memiliki data kalender akademik
                            $classIds = AcademicCalendar::query()
                                ->distinct()
                                ->pluck('class_id');

                            return SchoolClass::whereIn('id', $classIds)->pluck('name', 'id');
                        })
                        ->required(),
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Tanggal Mulai (Pekan Ini)')
                        ->default(now()->startOfWeek())
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Tanggal Selesai (Pekan Ini)')
                        ->default(now()->endOfWeek())
                        ->required(),
                    Forms\Components\Select::make('subject_id')
                        ->label('Mata Pelajaran (Opsional)')
                        ->relationship('subject', 'name')
                        ->placeholder('Semua Mata Pelajaran')
                        ->nullable(),
                ])
                ->action(function (array $data) {
                    $url = route('academic-calendar.weekly-preview', [
                        'class_id'   => $data['class_id'],
                        'start_date' => $data['start_date'],
                        'end_date'   => $data['end_date'],
                        'subject_id' => $data['subject_id'] ?? null,
                    ]);

                    return $this->js("window.open('{$url}', '_blank');");
                }),

            // Tombol Cetak Pemetaan Kalender Semester
            Actions\Action::make('printCalendarPdf')
                ->label('Cetak Pemetaan Kalender')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('class_id')
                        ->label('Kelas')
                        ->options(function () {
                            $classIds = AcademicCalendar::query()
                                ->distinct()
                                ->pluck('class_id');

                            return SchoolClass::whereIn('id', $classIds)->pluck('name', 'id');
                        })
                        ->required(),
                    Forms\Components\Select::make('semester')
                        ->label('Semester')
                        ->options([
                            '1' => 'Semester 1 (Ganjil)',
                            '2' => 'Semester 2 (Genap)',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('year')
                        ->label('Tahun Ajaran / Kalender')
                        ->numeric()
                        ->default(date('Y'))
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('academic-calendar.preview', [
                        'class_id' => $data['class_id'],
                        'semester' => $data['semester'],
                        'year'     => $data['year'],
                    ]);

                    return $this->js("window.open('{$url}', '_blank');");
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}