<?php

namespace App\Filament\Admin\Resources\Subjects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class SubjectForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(10)
                    ->label('Kode'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100)
                    ->label('Nama Pelajaran'),

                Forms\Components\Select::make('category')
                    ->options([
                        'agama' => 'Agama',
                        'umum' => 'Umum',
                        'muatan_lokal' => 'Muatan Lokal',
                    ])
                    ->required()
                    ->label('Kategori'),

                // Forms\Components\Select::make('order_number')
                //     ->options([
                //         '1' => '1',
                //         '2' => '2',
                //         '3' => '3',
                //         '4' => '4',
                //         '5' => '5',
                //         '6' => '6',
                //     ])
                //     ->required()
                //     ->label('Urutan'),

                Forms\Components\TextInput::make('urutan')
                    ->required()
                    ->maxLength(2)
                    ->label('Urutan'),
            ]);
    }
}
