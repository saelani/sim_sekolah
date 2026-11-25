<?php

namespace App\Filament\Admin\Resources\StudentSavings\Tables;

use App\Models\SchoolClass;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;


class StudentSavingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // 1. Filter query data berdasarkan user yang login
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Jika user adalah admin, tampilkan semua data
                if ($user->role === 'admin') {
                    return $query;
                }

                // Jika bukan admin, hanya tampilkan data miliknya sendiri
                // (Sesuaikan 'user_id' dengan nama kolom kepemilikan di database Anda)
                return $query->where('user_id', $user->id);
            })
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                // Relasi bertingkat: studentSavings -> student -> schoolClass
                Tables\Columns\TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in'    => 'success',
                        'out'   => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in'    => 'Setor',
                        'out'   => 'Tarik',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recorder.name')
                    ->label('Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_id')
                    ->label('Filter Kelas')
                    ->relationship('student.schoolClass', 'name') // Menggunakan relasi bertingkat langsung pada filter
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'in'  => 'Setor',
                        'out' => 'Tarik',
                    ]),
            ])
            ->actions([
                EditAction::make()->color('warning'),
                DeleteAction::make()->color('danger'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}