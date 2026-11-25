<?php

namespace App\Filament\Admin\Resources\Akademik\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class FinalScoreTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enrollment.student_name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mapel')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('formative_avg')
                    ->label('Rata-rata Formatif')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sumative_slm_avg')
                    ->label('Rata-rata Sumatif SLM')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sumative_sas')
                    ->label('Rata-rata Sumatif SAS')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('final_score')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
