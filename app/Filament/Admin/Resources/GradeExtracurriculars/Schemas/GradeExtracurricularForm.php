<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class GradeExtracurricularForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100)
                    ->label('Nama Kegiatan'),

                // Mengubah input teks pembina menjadi Select relasi ke tabel teachers
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name') // Pastikan model Teacher memiliki relasi/kolom 'name' untuk nama guru
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->label('Guru Pembina'),
            ]);
    }
}