<?php

namespace App\Filament\Admin\Resources\StudentSavings\Schemas;

use App\Models\Student;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

class StudentSavingForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Detail Transaksi Tabungan')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->label('Siswa')
                            ->relationship('student', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Student $record) => "{$record->name} (" . ($record->schoolClass?->name ?? 'Tanpa Kelas') . ")")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => Auth::id()),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Transaksi')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Jenis Transaksi')
                            ->options([
                                'in'  => 'Setor (Masuk)',
                                'out' => 'Tarik (Keluar)',
                            ])
                            ->default('in')
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}