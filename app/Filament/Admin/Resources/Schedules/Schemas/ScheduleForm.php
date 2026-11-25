<?php

namespace App\Filament\Admin\Resources\Schedules\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class ScheduleForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->label('Kelas'),

                // Di dalam method configure($schema) atau skema form Anda:
                Select::make('subject_id')
                    ->relationship('subject', 'name', function (Builder $query) {
                        $user = auth()->user();

                        // Jika Admin atau Headmaster, tampilkan semua mata pelajaran
                        if ($user->role === 'admin' || $user->role === 'headmaster') {
                            return $query;
                        }

                        // Jika Guru biasa, hanya tampilkan mata pelajaran miliknya sendiri
                        $teacherId = optional($user->teacher()->first())->id;
                        return $query->where('teacher_id', $teacherId);
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                
                // Forms\Components\Select::make('subject_id')
                //     ->relationship('subject', 'name')
                //     ->required()
                //     ->label('Mapel'),

                Forms\Components\TextInput::make('day_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Hari'),

                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Waktu Mulai'),

                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->label('Waktu Selesai'),

                Forms\Components\TextInput::make('room_name')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Ruangan'),
            ]);
    }
}
