<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\LearningObjectiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Subject;


class ListLearningObjectives extends ListRecords
{
    protected static string $resource = LearningObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('printPdf')
            ->label('Cetak Laporan TP')
            ->icon('heroicon-o-printer')
            ->color('success')
            ->form([
                Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->options(Subject::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('level')
                    ->label('Level / Tingkat')
                    ->options([
                        // Sesuaikan opsi level Anda
                        '1' => 'Level 1',
                        '2' => 'Level 2',
                        '3' => 'Level 3',
                        '4' => 'Level 4',
                        '5' => 'Level 5',
                        '6' => 'Level 6',
                    ]),
                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ]),
            ])
            ->action(function (array $data) {
                // Redirect ke route controller PDF dengan parameter
                $url = route('learning-objective.preview', [
                    'subject_id' => $data['subject_id'],
                    'level'      => $data['level'] ?? null,
                    'semester'   => $data['semester'] ?? null,
                ]);

                // return redirect()->to($url);
                return $this->js("window.open('{$url}', '_blank');");

            })
            
            // ->openUrlInNewTab(), // Membuka preview di tab baru
        ];
    }
}
