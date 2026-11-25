<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class P5ProjectForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->nullable()
                    ->label('Guru'),

                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'year')
                    ->required()
                    ->label('Tahun Ajaran'),

                Forms\Components\Select::make('phase')
                    ->options([
                        'A' => 'Fase A',
                        'B' => 'Fase B',
                        'C' => 'Fase C',
                    ])
                    ->required()
                    ->label('Fase'),

                Forms\Components\TextInput::make('theme')
                    ->required()
                    ->maxLength(255)
                    ->label('Tema'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(4)
                    ->label('Deskripsi'),
            ]);
    }
}
