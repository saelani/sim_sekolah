<?php

namespace App\Filament\Admin\Resources\Akademik\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class ReportCardForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('enrollment_id')
                    ->relationship('enrollment', 'student_name')
                    ->required()
                    ->label('Siswa'),

                Forms\Components\Select::make('sick')
                    ->options([
                        '1' => 'Sakit',
                        '0' => 'Tidak Sakit',
                    ])
                    ->label('Sakit'),

                Forms\Components\Select::make('permission')
                    ->options([
                        '1' => 'Izin',
                        '0' => 'Tidak Izin',
                    ])
                    ->label('Izin'),

                Forms\Components\Select::make('unexcused')
                    ->options([
                        '1' => 'Tidak Izin',
                        '0' => 'Tidak Izin',
                    ])
                    ->label('Tanpa Izin'),

                Forms\Components\Textarea::make('extracurricular_notes')
                    ->rows(3)
                    ->label('Catatan Ekstrakurikuler'),

                Forms\Components\Textarea::make('teacher_notes')
                    ->rows(3)
                    ->label('Catatan Guru'),

                Forms\Components\TextInput::make('height_weight')
                    ->maxLength(100)
                    ->label('Tinggi Berat'),

                Forms\Components\Textarea::make('health_notes')
                    ->rows(3)
                    ->label('Catatan Kesehatan'),

                Forms\Components\Toggle::make('is_promoted')
                    ->label('Dilanjutkan'),

                Forms\Components\Toggle::make('is_locked')
                    ->label('Dikunci'),

                Forms\Components\TextInput::make('place_date_printed')
                    ->maxLength(150)
                    ->label('Tempat Tanggal Cetak'),

                Forms\Components\TextInput::make('printed_at')
                    ->maxLength(150)
                    ->label('Dicetak Pada'),
            ]);
    }
}
