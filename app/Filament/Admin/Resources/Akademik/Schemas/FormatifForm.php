<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use App\Models\Enrollment;
use App\Models\LearningObjective;
use App\Models\Subject;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class FormatifForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Select Siswa (Pencarian via Enrollment -> Student)
                Select::make('enrollment_id')
                    ->label('Siswa')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(function (string $search) {
                        return Enrollment::query()
                            ->whereHas('student', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%")
                                      ->orWhere('nis', 'like', "%{$search}%");
                            })
                            ->with('student')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($enrollment) {
                                $name = $enrollment->student?->name ?? 'Siswa';
                                $nis = $enrollment->student?->nis ? " ({$enrollment->student->nis})" : '';
                                return [$enrollment->id => "{$name}{$nis}"];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $enrollment = Enrollment::with('student')->find($value);
                        if (! $enrollment) return '-';
                        $name = $enrollment->student?->name ?? 'Siswa';
                        $nis = $enrollment->student?->nis ? " ({$enrollment->student->nis})" : '';
                        return "{$name}{$nis}";
                    }),

                // 2. Select Mata Pelajaran (Langkah 1 Bertingkat)
                Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->options(Subject::pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('learning_objective_id', null))
                    ->dehydrated(false),

                // 3. Select Tujuan Pembelajaran (Langkah 2 Bertingkat)
                Select::make('learning_objective_id')
                    ->label('Tujuan Pembelajaran')
                    ->searchable()
                    ->required()
                    ->options(function (Get $get) {
                        $subjectId = $get('subject_id');

                        if (! $subjectId) {
                            return [];
                        }

                        return LearningObjective::where('subject_id', $subjectId)
                            ->get()
                            ->mapWithKeys(function ($lo) {
                                return [$lo->id => "{$lo->code} - {$lo->description}"];
                            });
                    })
                    ->disabled(fn (Get $get) => ! $get('subject_id')),

                TextInput::make('type')
                    ->label('Tipe')
                    ->maxLength(255),

                Select::make('is_achieved')
                    ->label('Telah Dicapai')
                    ->options([
                        '1' => 'Achieved',
                        '0' => 'Not Achieved',
                    ])
                    ->default('1'),

                TextInput::make('score')
                    ->label('Score')
                    ->numeric(),
            ]);
    }
}