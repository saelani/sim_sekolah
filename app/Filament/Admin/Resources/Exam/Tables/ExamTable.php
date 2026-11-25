<?php

namespace App\Filament\Admin\Resources\Exam\Tables;

use App\Filament\Admin\Actions\BroadcastWaExamAction;
use App\Filament\Admin\Actions\GenerateAiQuestionsAction;
use App\Filament\Admin\Actions\ImportQuestionsAction;
use App\Models\Exam;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class ExamTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Ujian')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Exam $record) => 'Mata Pelajaran: '.($record->subject?->name ?? '-')),

                TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('token')
                    ->label('Token')
                    ->badge()
                    ->color('warning')
                    ->copyable()
                    ->copyMessage('Token disalin!'),

                TextColumn::make('duration_minutes')
                    ->label('Durasi')
                    ->numeric()
                    ->suffix(' Mnt')
                    ->alignCenter(),

                TextColumn::make('questions_count')
                    ->label('Jml Soal')
                    ->counts('questions')
                    ->badge()
                    ->alignCenter(),

                TextColumn::make('is_active')
                    ->label('Aktif')
                    ->badge()
                    ->color(fn ($state): string => (bool) $state ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state): string => (bool) $state ? 'Aktif' : 'Non-Aktif'),


                // ... di dalam ->columns([...]) pada ExamTable.php:
                TextColumn::make('start_time')
                    ->label('Pelaksanaan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(function (Exam $record): string {
                        if (! $record->end_time) {
                            return 's/d -';
                        }

                        $end = $record->end_time instanceof \DateTimeInterface
                            ? $record->end_time
                            : Carbon::parse($record->end_time);

                        return 's/d ' . $end->format('d M Y, H:i');
                    }),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                SelectFilter::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name', function ($query) {
                        $user = Auth::user();
                        if ($user && $user->role === 'teacher') {
                            $teacherId = $user->teacher?->id;

                            return $query->whereHas('teacherSubjectClasses', function ($q) use ($teacherId) {
                                $q->where('teacher_id', $teacherId);
                            });
                        }

                        return $query;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ActionGroup::make([
                    GenerateAiQuestionsAction::make(),
                    BroadcastWaExamAction::make(),
                    ImportQuestionsAction::make(),
                ])
                    ->label('Opsi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
                    ->tooltip('Menu Aksi'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}