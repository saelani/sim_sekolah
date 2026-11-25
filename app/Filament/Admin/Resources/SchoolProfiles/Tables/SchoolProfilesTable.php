<?php

namespace App\Filament\Admin\Resources\SchoolProfiles\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class SchoolProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-logo.png')),

                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('info'),

                Tables\Columns\TextColumn::make('school_name')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('headmaster_name')
                    ->label('Kepala Sekolah')
                    ->default('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('accreditation')
                    ->label('Akreditasi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('accreditation')
                    ->label('Akreditasi')
                    ->options([
                        'A' => 'Akreditasi A',
                        'B' => 'Akreditasi B',
                        'C' => 'Akreditasi C',
                    ]),
            ])
            ->actions([
                EditAction::make()->color('warning'),
                DeleteAction::make()->color('danger'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}