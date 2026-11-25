<?php

namespace App\Filament\Admin\Resources\Schedules;

use App\Filament\Admin\Resources\Schedules\Pages\CreateSchedule;
use App\Filament\Admin\Resources\Schedules\Pages\EditSchedule;
use App\Filament\Admin\Resources\Schedules\Pages\ListSchedules;
use App\Filament\Admin\Resources\Schedules\Schemas\ScheduleForm;
use App\Filament\Admin\Resources\Schedules\Tables\SchedulesTable;
use App\Models\Schedule;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static ?string $navigationLabel = 'Jadwal Pelajaran'; 

    protected static ?string $modelLabel = 'Jadwal Pelajaran';
    protected static ?string $pluralModelLabel = 'Jadwal Pelajaran';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }
    
    /**
     * Membatasi data yang tampil di tabel berdasarkan relasi bertingkat (Schedule -> Subject -> Teacher)
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user adalah Admin atau Headmaster, tampilkan semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return $query;
        }

        // Ambil ID dari relasi teacher milik user yang login
        $teacherId = optional($user->teacher()->first())->id;

        // Filter jadwal bertingkat: Schedule -> Class -> Teacher
        return $query->whereHas('schoolClass', function ($classQuery) use ($teacherId) {
            $classQuery->where('teacher_id', $teacherId); // Sesuaikan kolom foreign key guru di tabel kelas
        });
    }

    public static function form(Schema $schema): Schema
    {
        return ScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchedulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchedules::route('/'),
            'create' => CreateSchedule::route('/create'),
            'edit' => EditSchedule::route('/{record}/edit'),
        ];
    }
}