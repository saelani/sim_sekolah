<?php

namespace App\Filament\Admin\Resources\ExamSession\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExamSessionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exam.title')
                    ->label('Ujian')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('submitted_at')
                    ->label('Selesai')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Belum Selesai'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => ['ongoing', 'in_progress'],
                        'success' => ['submitted', 'completed'],
                        'danger'  => 'blocked',
                    ]),

                TextColumn::make('score')
                    ->label('Skor')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                // BulkActionGroup::make([]),
            ]);
    }
}