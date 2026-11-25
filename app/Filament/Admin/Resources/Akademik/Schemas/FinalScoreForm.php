<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class FinalScoreForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('enrollment_id')
                    // Sesuaikan dengan nama relasi di model FinalScore (huruf besar/kecil)
                    ->relationship('enrollment', 'id') 
                    // Atau jika ingin menampilkan nama siswa melalui relasi ke tabel students:
                    // ->getOptionLabelFromRecordUsing(fn ($record) => $record->student?->name ?? 'Siswa')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Siswa'),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Mata Pelajaran'),

                Forms\Components\TextInput::make('formative_avg')
                    ->numeric()
                    ->step(0.01)
                    ->label('Rata-rata Formatif'),

                Forms\Components\TextInput::make('sumative_slm_avg')
                    ->numeric()
                    ->step(0.01)
                    ->label('Rata-rata Sumatif Lingkup Materi'),

                Forms\Components\TextInput::make('sumative_sas')
                    ->numeric()
                    ->step(0.01)
                    ->label('Nilai SAS / PAT'),

                Forms\Components\TextInput::make('final_score')
                    ->numeric()
                    ->step(0.01)
                    ->label('Nilai Akhir Rapor'),

                Forms\Components\Textarea::make('highest_achieved_description')
                    ->rows(3)
                    ->columnSpanFull()
                    ->label('Deskripsi Kompetensi Tertinggi (Sangat Dikuasai)'),

                Forms\Components\Textarea::make('lowest_achieved_description')
                    ->rows(3)
                    ->columnSpanFull()
                    ->label('Deskripsi Kompetensi Perlu Peningkatan'),
            ]);
    }
}