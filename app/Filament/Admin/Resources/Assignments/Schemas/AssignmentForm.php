<?php

namespace App\Filament\Admin\Resources\Assignments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class AssignmentForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->label('Kelas'),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->label('Mapel'),

                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->nullable()
                    ->label('Guru'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(4)
                    ->label('Deskripsi'),

                Forms\Components\DatePicker::make('due_date')
                    ->required()
                    ->label('Tanggal Jatuh Tempo'),
            ]);
    }
}
