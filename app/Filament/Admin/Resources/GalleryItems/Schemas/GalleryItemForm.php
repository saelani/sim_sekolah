<?php

namespace App\Filament\Admin\Resources\GalleryItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('gallery_id')
                    ->relationship('Gallery', 'title')
                    ->required()
                    ->label('Galeri Induk'),

                Select::make('type')
                    ->options([
                        'image' => 'Gambar',
                        'video' => 'Video',
                        'file' => 'File',
                    ])
                    ->required()
                    ->label('Tipe Item'),

                TextInput::make('file_or_url')
                    ->required()
                    ->maxLength(255)
                    ->label('File / URL')
                    ->helperText('Contoh: galleries/gal-1.jpg'),

                TextInput::make('caption')
                    ->maxLength(500)
                    ->label('Caption'),
            ]);
    }
}