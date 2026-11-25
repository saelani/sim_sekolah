<?php

namespace App\Filament\Admin\Resources\Akademik\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class FormatifTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('enrollment.student.name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('learningObjective.code')
                    ->label('Tujuan Pembelajaran')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe'),

                Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_achieved')
                    ->label('Telah Dicapai')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                // EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}