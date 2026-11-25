<?php

namespace App\Filament\Admin\Resources\Akademik\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class ReportCardTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enrollment.student_name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sick')
                    ->label('Sakit')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('permission')
                    ->label('Izin')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'warning' : 'success'),

                Tables\Columns\TextColumn::make('unexcused')
                    ->label('Tanpa Izin')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('height_weight')
                    ->label('Tinggi Berat'),

                Tables\Columns\TextColumn::make('health_notes')
                    ->label('Catatan Kesehatan')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('is_promoted')
                    ->label('Dilanjutkan')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('is_locked')
                    ->label('Dikunci')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'secondary' : 'gray'),

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
