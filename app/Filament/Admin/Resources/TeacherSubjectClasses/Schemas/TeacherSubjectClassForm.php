<?php

namespace App\Filament\Admin\Resources\TeacherSubjectClasses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class TeacherSubjectClassForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->label('Guru'),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->label('Mapel'),

                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->label('Kelas'),

                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'year')
                    ->required()
                    ->label('Tahun Ajaran'),
            ]);
    }
}
