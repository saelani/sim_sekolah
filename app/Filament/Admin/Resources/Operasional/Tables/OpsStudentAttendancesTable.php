<?php

namespace App\Filament\Admin\Resources\Operasional\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Select;
use App\Filament\Admin\Resources\Operasional\OpsStudentAttendanceResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;

class OpsStudentAttendancesTable
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
                Tables\Columns\TextColumn::make('student.schoolClass.name')
                // Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Wali Kelas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'danger',
                        'Izin' => 'warning',
                        'Alpa' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                // 2. Batasi tombol Edit hanya untuk admin atau pemilik data
                EditAction::make()
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if ($user->role === 'admin') {
                            return true;
                        }
                        return $record->user_id === $user->id;
                    }),

                // 3. Batasi tombol Delete hanya untuk admin atau pemilik data
                DeleteAction::make()
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if ($user->role === 'admin') {
                            return true;
                        }
                        return $record->user_id === $user->id;
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}