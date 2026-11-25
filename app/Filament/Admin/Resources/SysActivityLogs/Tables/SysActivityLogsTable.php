<?php

namespace App\Filament\Admin\Resources\SysActivityLogs\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class SysActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Nama Log')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Tipe Subjek')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID Subjek')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('causer_type')
                    ->label('Tipe Penyebab')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('causer_id')
                    ->label('ID Penyebab')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
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
