<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('PostCategory', 'name')
                    ->required()
                    ->label('Kategori Berita'),

                Select::make('user_id')
                    ->relationship('User', 'name')
                    ->required()
                    ->label('Penulis'),

                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul'),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->label('Slug'),
                    ]),

                Textarea::make('excerpt')
                    ->required()
                    ->maxLength(500)
                    ->rows(2)
                    ->label('Ekstrak / Pengantar'),

                Textarea::make('content')
                    ->required()
                    ->rows(12)
                    ->label('Isi Berita'),

                Grid::make(2)
                    ->schema([
                        TextInput::make('thumbnail_path')
                            ->label('Path Thumbnail (storage)')
                            ->helperText('Contoh: posts/thumb-post-1.png'),

                        Toggle::make('is_published')
                            ->label('Publikasi'),
                    ]),

                DatePicker::make('published_at')
                    ->label('Waktu Terbit')
                    ->displayFormat('Y-m-d H:i'),
            ]);
    }
}