<?php

namespace App\Filament\Admin\Resources\SysActivityLogs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class SysActivityLogForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('log_name')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Nama Log'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(4)
                    ->label('Deskripsi'),

                Forms\Components\TextInput::make('subject_type')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Tipe Subjek'),

                Forms\Components\TextInput::make('subject_id')
                    ->nullable()
                    ->numeric()
                    ->label('ID Subjek'),

                Forms\Components\TextInput::make('causer_type')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Tipe Penyebab'),

                Forms\Components\TextInput::make('causer_id')
                    ->nullable()
                    ->numeric()
                    ->label('ID Penyebab'),

                Forms\Components\Textarea::make('properties')
                    ->nullable()
                    ->rows(4)
                    ->columns(2)
                    ->label('Properties (JSON)'),

                Forms\Components\TextInput::make('batch_uuid')
                    ->nullable()
                    ->maxLength(36)
                    ->label('Batch UUID'),
            ]);
    }
}
