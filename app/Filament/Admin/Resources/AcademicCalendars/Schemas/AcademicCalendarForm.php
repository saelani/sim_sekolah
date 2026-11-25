<?php

namespace App\Filament\Admin\Resources\AcademicCalendars\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class AcademicCalendarForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Kegiatan'),

                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai'),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('Tanggal Selesai'),

                Forms\Components\Textarea::make('notes')
                    ->rows(4)
                    ->label('Catatan'),

                Forms\Components\Select::make('activity_type')
                    ->options([
                        'kbm' => 'KBM',
                        'pelajaran' => 'Pelajaran',
                        'upacara' => 'Upacara',
                        'lainnya' => 'Lainnya',
                    ])
                    ->default('kbm')
                    ->label('Tipe Kegiatan'),
            ]);
    }
}
