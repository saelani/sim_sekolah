<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumativeScopeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Subject;

class ListSumativeScopes extends ListRecords
{
    protected static string $resource = SumativeScopeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('printPdf')
                ->label('Cetak Laporan Lingkup Materi')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Select::make('subject_id')
                        ->label('Mata Pelajaran')
                        ->options(Subject::pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    Select::make('phase')
                        ->label('Fase')
                        ->options([
                            'A' => 'Fase A (Kelas 1-2)',
                            'B' => 'Fase B (Kelas 3-4)',
                            'C' => 'Fase C (Kelas 5-6)',
                        ]),
                    Select::make('semester')
                        ->label('Semester')
                        ->options([
                            '1' => 'Semester 1',
                            '2' => 'Semester 2',
                        ]),
                ])
                ->action(function (array $data) {
                    // Redirect ke route preview PDF Sumative Scope dengan parameter filter
                    $url = route('pdf.sumative-scope.preview', [
                        'subject_id' => $data['subject_id'],
                        'phase'      => $data['phase'] ?? null,
                        'semester'   => $data['semester'] ?? null,
                    ]);

                    return $this->js("window.open('{$url}', '_blank');");
                }),
        ];
    }
}