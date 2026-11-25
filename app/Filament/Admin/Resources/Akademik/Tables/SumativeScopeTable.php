<?php

namespace App\Filament\Admin\Resources\Akademik\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Teacher; // Pastikan model Teacher di-import

class SumativeScopeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Jika admin atau headmaster, tampilkan semua data
                if ($user->role === 'admin' || $user->role === 'headmaster') {
                    return $query;
                }

                // Cari data guru yang terikat dengan user yang sedang login secara aman
                $teacher = Teacher::where('user_id', $user->id)->first();
                $teacherId = $teacher ? $teacher->id : null;

                // Filter data berdasarkan teacher_id milik guru tersebut
                return $query->where('teacher_id', $teacherId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mapel')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('phase')
                    ->label('Fase')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'info',
                        'B' => 'warning',
                        'C' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'primary' : 'secondary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Scope')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make()
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if ($user->role === 'admin' || $user->role === 'headmaster') {
                            return true;
                        }
                        $teacher = Teacher::where('user_id', $user->id)->first();
                        return $teacher && $record->teacher_id === $teacher->id;
                    }),

                DeleteAction::make()
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if ($user->role === 'admin' || $user->role === 'headmaster') {
                            return true;
                        }
                        $teacher = Teacher::where('user_id', $user->id)->first();
                        return $teacher && $record->teacher_id === $teacher->id;
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}