<?php

namespace App\Filament\Admin\Resources\Students\Tables;

use App\Models\SchoolProfile;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class StudentsTable
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
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'primary' : 'danger'),

                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama'),

                Tables\Columns\TextColumn::make('SchoolClass.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Profil Sekolah (referensi) — berasal dari SchoolProfileResource.
                // Tampilan di tabel siswa; akses CRUD tetap terbatas pada Admin & Headmaster.
                Tables\Columns\TextColumn::make('School Name')
                    ->label('Sekolah')
                    ->formatStateUsing(fn () => SchoolProfile::first()?->school_name ?? '—'),

                Tables\Columns\TextColumn::make('Principal')
                    ->label('Kepala Sekolah')
                    ->formatStateUsing(fn () => SchoolProfile::first()?->headmaster_name ?? '—'),

                Tables\Columns\TextColumn::make('Accreditation')
                    ->label('Akreditasi')
                    ->formatStateUsing(fn () => SchoolProfile::first()?->accreditation ?? '—')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('Address')
                    ->label('Alamat')
                    ->formatStateUsing(fn () => SchoolProfile::first()?->address ?? '—'),
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
