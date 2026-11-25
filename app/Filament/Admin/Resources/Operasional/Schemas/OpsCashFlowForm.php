<?php

namespace App\Filament\Admin\Resources\Operasional\Schemas;

use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

class OpsCashFlowForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();

        return $schema
            ->components([
                Section::make('Informasi Arus Kas')
                    ->description('Silakan isi detail transaksi kas di bawah ini.')
                    ->schema([
                        Hidden::make('user_id')
                            ->default($user ? $user->id : null),

                        Select::make('academic_year_id')
                            ->label('Tahun Ajaran')
                            ->relationship('academicYear', 'year') // Pastikan relasi academicYear ada di model OpsCashFlow jika diperlukan
                            ->default(fn () => AcademicYear::where('is_active', true)->value('id'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('class_id')
                            ->label('Kelas (Opsional)')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->nullable()
                            ->searchable(),

                        DatePicker::make('date')
                            ->label('Tanggal Transaksi')
                            ->default(now())
                            ->required(),

                        Select::make('type')
                            ->label('Jenis Arus Kas')
                            ->options([
                                'in'  => 'Pemasukan (Masuk)',
                                'out' => 'Pengeluaran (Keluar)',
                            ])
                            ->default('in')
                            ->required(),

                        TextInput::make('amount')
                            ->label('Nominal (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Keterangan / Uraian')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getSchema(): Schema
    {
        return static::configure(Schema::make());
    }
}