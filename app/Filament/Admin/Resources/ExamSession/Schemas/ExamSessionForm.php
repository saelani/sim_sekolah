<?php

namespace App\Filament\Admin\Resources\ExamSession\Schemas;

use App\Models\Exam;
use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExamSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cbt_exam_id')
                    ->label('Ujian CBT')
                    ->options(Exam::pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('student_id')
                    ->label('Siswa')
                    ->options(Student::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'ongoing'     => 'Sedang Mengerjakan (Ongoing)',
                        'in_progress' => 'Sedang Mengerjakan (In Progress)',
                        'submitted'   => 'Selesai (Submitted)',
                        'completed'   => 'Selesai (Completed)',
                        'blocked'     => 'Diblokir',
                    ])
                    ->required()
                    ->default('in_progress'),

                DateTimePicker::make('submitted_at')
                    ->label('Waktu Selesai')
                    ->seconds(false)
                    ->nullable(),

                TextInput::make('score')
                    ->label('Skor')
                    ->numeric()
                    ->nullable(),

                TextInput::make('total_score')
                    ->label('Total Skor')
                    ->numeric()
                    ->nullable(),
            ]);
    }
}