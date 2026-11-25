<?php

namespace App\Filament\Admin\Resources\Akademik\Tables;

use Filament\Tables;
use App\Models\SchoolClass; // Pastikan modelnya di-import
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Teacher;

class LearningObjectiveTable
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

                // Ambil ID guru secara aman dari user yang login
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

                Tables\Columns\TextColumn::make('sumativeScope.name')
                    ->label('Sumative Scope')
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
                
                

                Tables\Columns\TextColumn::make('level')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn (string $state): string => $state === '1' ? 'primary' : 'secondary'),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
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
