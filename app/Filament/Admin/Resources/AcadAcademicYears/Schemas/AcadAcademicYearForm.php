<?php

namespace App\Filament\Admin\Resources\AcadAcademicYears\Schemas;

use Filament\Schemas\Schema; // Gunakan Schema dari Filament 4
use Filament\Forms;

class AcadAcademicYearForm
{
    public static function configure(Schema $form): Schema // Ubah tipe data parameter dan return type menjadi Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->required()
                    ->label('Tahun Ajaran'),
                
                Forms\Components\Select::make('semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->required()
                    ->label('Semester'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->required(),
            ]);
    }
}