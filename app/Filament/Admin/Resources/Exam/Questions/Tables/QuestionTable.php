<?php

namespace App\Filament\Admin\Resources\Exam\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exam.title')
                    ->label('Ujian')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'single_choice'   => 'info',
                        'multiple_choice' => 'primary',
                        'short_answer'    => 'warning',
                        'essay'           => 'danger',
                        default           => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'single_choice'   => 'Pilihan Tunggal',
                        'multiple_choice' => 'Pilihan Ganda',
                        'short_answer'    => 'Isian Singkat',
                        'essay'           => 'Uraian / Essay',
                        default           => $state ?? '-',
                    }),

                TextColumn::make('question_text')
                    ->label('Isi Soal')
                    ->html()
                    ->limit(80)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('score_weight')
                    ->label('Bobot')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}