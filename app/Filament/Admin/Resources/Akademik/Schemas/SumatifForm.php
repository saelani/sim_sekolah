<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\SumativeScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SumatifForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
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
                                $nis  = $enrollment->student?->nis ? " ({$enrollment->student->nis})" : '';

                                return [(string) $enrollment->id => "{$name}{$nis}"];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $enrollment = Enrollment::with('student')->find($value);
                        if (! $enrollment) {
                            return '-';
                        }
                        $name = $enrollment->student?->name ?? 'Siswa';
                        $nis  = $enrollment->student?->nis ? " ({$enrollment->student->nis})" : '';

                        return "{$name}{$nis}";
                    }),

                // 2. Select Mata Pelajaran (Trigger Live untuk Scope)
                // Select::make('subject_id')
                //     ->label('Mata Pelajaran')
                //     ->options(Subject::pluck('name', 'id'))
                //     ->searchable()
                //     ->live()
                //     ->afterStateUpdated(fn (Set $set) => $set('sumative_scope_id', null))
                //     ->dehydrated(false),

                // Ubah bagian ini di SumatifForm.php:
                Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->options(Subject::pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('sumative_scope_id', null))
                    ->required(), // <-- Ganti ->dehydrated(false) menjadi ->required()

                // 3. Select Scope / Lingkup Materi (Dinamis berdasarkan Subject)
                Select::make('sumative_scope_id')
                    ->label('Lingkup Materi (Scope)')
                    ->searchable()
                    ->required()
                    ->options(function (Get $get) {
                        $subjectId = $get('subject_id');

                        if (! $subjectId) {
                            return [];
                        }

                        return SumativeScope::where('subject_id', $subjectId)
                            ->get()
                            ->mapWithKeys(function ($scope) {
                                return [(string) $scope->id => $scope->name];
                            })
                            ->toArray();
                    })
                    ->disabled(fn (Get $get) => ! $get('subject_id')),

                // 4. Select Tipe Sumatif
                Select::make('type')
                    ->label('Tipe Sumatif')
                    ->options([
                        'SLM' => 'Sumatif Lingkup Materi (SLM)',
                        'STS' => 'Sumatif Tengah Semester (STS)',
                        'SAS' => 'Sumatif Akhir Semester (SAS)',
                    ])
                    ->required(),

                // 5. Field Nilai/Skor
                TextInput::make('score')
                    ->label('Nilai Sumatif')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->placeholder('0 - 100')
                    ->required(),
            ]);
    }
}