<?php

namespace App\Filament\Admin\Resources\Enrollments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class EnrollmentForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'year')
                    ->required()
                    ->label('Tahun Ajaran'),

                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->label('Kelas'),

                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required()
                    ->label('Siswa'),
            ]);
    }
}
