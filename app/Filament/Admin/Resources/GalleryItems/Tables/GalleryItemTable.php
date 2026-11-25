<?php

namespace App\Filament\Admin\Resources\GalleryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryItemTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gallery.title')
                    ->label('Galeri')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('type')
                    ->label('Tipe')
                    ->options([
                        'image' => 'Gambar',
                        'video' => 'Video',
                        'file' => 'File',
                    ])
                    ->sortable()
                    ->searchable(),
                TextColumn::make('file_or_url')
                    ->label('File / URL')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(40),
                TextColumn::make('created_at')
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