<?php

namespace App\Filament\Admin\Resources\SchoolClasses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class SchoolClassForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->label('Nama Kelas'),

                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'year')
                    ->required()
                    ->label('Tahun Ajaran'),

                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->label('Guru'),

                Forms\Components\Select::make('phase')
                    ->options([
                        'A' => 'Fase A',
                        'B' => 'Fase B',
                        'C' => 'Fase C',
                    ])
                    ->required()
                    ->label('Fase'),

                Forms\Components\Select::make('level')
                    ->options([
                        '1' => 'Tingkat 1',
                        '2' => 'Tingkat 2',
                        '3' => 'Tingkat 3',
                        '4' => 'Tingkat 4',
                        '5' => 'Tingkat 5',
                        '6' => 'Tingkat 6',
                    ])
                    ->required()
                    ->label('Tingkat'),
            ]);
    }
}
