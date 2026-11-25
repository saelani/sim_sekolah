<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class SumativeScopeForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->label('Mapel'),

                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->nullable()
                    ->label('Guru'),

                Forms\Components\Select::make('phase')
                    ->options([
                        'A' => 'Fase A',
                        'B' => 'Fase B',
                        'C' => 'Fase C',
                    ])
                    ->required()
                    ->label('Fase'),
                
                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->nullable()
                    ->label('Kelas'),

                Forms\Components\Select::make('semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->required()
                    ->label('Semester'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Scope'),
            ]);
    }
}
