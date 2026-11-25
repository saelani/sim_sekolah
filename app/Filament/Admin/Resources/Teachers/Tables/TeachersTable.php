<?php

namespace App\Filament\Admin\Resources\Teachers\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                ->label('Foto')
                ->disk('public') // Sesuaikan disk yang digunakan saat upload (misal: 'public')
                ->circular(), // Opsional: membuat tampilan foto menjadi berbentuk lingkaran (bulat)
                // ->square() // Atau gunakan ->square() jika ingin bentuk kotak
                // ->size(40) // Opsional: mengatur ukuran lebar/tinggi gambar dalam piksel

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->getStateUsing(function ($record) {
                        // Menggabungkan front_title, name, dan back_title
                        $front = $record->front_title ? trim($record->front_title) . ' ' : '';
                        $name = trim($record->name ?? '');
                        $back = $record->back_title ? ', ' . trim($record->back_title) : '';

                        return trim($front . $name . $back);
                    })
                    ->sortable(query: function ($query, string $direction) {
                        // Agar tetap bisa disort berdasarkan nama utama
                        return $query->orderBy('name', $direction);
                    })
                    ->searchable(query: function ($query, string $search) {
                        // Agar pencarian mencakup gelar depan, nama, maupun gelar belakang
                        return $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('front_title', 'like', "%{$search}%")
                                    ->orWhere('back_title', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nuptk')
                    ->label('NUPTK')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'primary' : 'danger'),

                Tables\Columns\TextColumn::make('role_type')
                    ->label('Jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'headmaster' => 'warning',
                        'class_teacher' => 'info',
                        'subject_teacher' => 'secondary',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                    Tables\Columns\TextColumn::make('updated_at')
                        ->label('Diperbarui')
                        ->dateTime()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->actions([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
    }
}
