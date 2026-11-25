<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use App\Models\Enrollment;

class ExtracurricularScoreForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('enrollment_id')
                    ->label('Siswa')
                    ->required()
                    ->searchable()
                    // Mengambil opsi berdasarkan relasi bertingkat dengan aman
                    ->getSearchResultsUsing(fn (string $search) => Enrollment::query()
                        ->with('student')
                        ->whereHas('student', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%"); // Sesuaikan 'name' jika kolomnya 'nama'
                        })
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(function ($enrollment) {
                            // Tampilkan nama siswa (dan info tambahan jika perlu, misal kelas)
                            $studentName = $enrollment->student->name ?? 'Tidak diketahui';
                            return [$enrollment->id => $studentName];
                        }))
                    ->getOptionLabelUsing(fn ($value): ?string => 
                        Enrollment::with('student')->find($value)?->student->name
                    ),

                Forms\Components\Select::make('extracurricular_id')
                    ->relationship('extracurricular', 'name')
                    ->required()
                    ->label('Ekstrakurikuler'),

                Forms\Components\Select::make('grade')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                    ])
                    ->required()
                    ->label('Grade'),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Deskripsi'),
            ]);
    }
}