<?php

namespace App\Filament\Admin\Resources\Galleries\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Galeri'),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->label('Slug'),
                    ]),

                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(500)
                    ->label('Deskripsi'),
            ]);
    }
}