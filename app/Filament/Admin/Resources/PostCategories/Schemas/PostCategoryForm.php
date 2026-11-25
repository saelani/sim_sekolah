<?php

namespace App\Filament\Admin\Resources\PostCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tokenizer;

class PostCategoryForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->label('Slug'),
            ]);
    }
}
