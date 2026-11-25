<?php

namespace App\Filament\Admin\Resources\AcademicCalendars;

use App\Filament\Admin\Resources\AcademicCalendars\Pages\CreateAcademicCalendar;
use App\Filament\Admin\Resources\AcademicCalendars\Pages\EditAcademicCalendar;
use App\Filament\Admin\Resources\AcademicCalendars\Pages\ListAcademicCalendars;
use App\Filament\Admin\Resources\AcademicCalendars\Schemas\AcademicCalendarForm;
use App\Filament\Admin\Resources\AcademicCalendars\Tables\AcademicCalendarsTable;
use App\Models\AcademicCalendar;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder; // <-- Jangan lupa import ini
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcademicCalendarResource extends Resource
{
    protected static ?string $model = AcademicCalendar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $navigationLabel = 'Kalender Akademik'; 

    protected static ?string $modelLabel = 'Kalender Akademik';
    protected static ?string $pluralModelLabel = 'Kalender Akademik';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }
    
    /**
     * Membatasi data kalender akademik berdasarkan guru yang login
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

        // Filter data berdasarkan relasi learningObjective yang memiliki teacher_id, 
        // atau langsung melalui subject/teacher jika ada. Di sini kita filter lewat learningObjective.
        return $query->whereHas('learningObjective', function ($subQuery) use ($teacherId) {
            $subQuery->where('teacher_id', $teacherId)
                     ->orWhereHas('sumativeScope', function ($scopeQuery) use ($teacherId) {
                         $scopeQuery->where('teacher_id', $teacherId);
                     });
        });
    }

    public static function form(Schema $schema): Schema
    {
        return AcademicCalendarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicCalendarsTable::configure($table);
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
            'index' => ListAcademicCalendars::route('/'),
            'create' => CreateAcademicCalendar::route('/create'),
            'edit' => EditAcademicCalendar::route('/{record}/edit'),
        ];
    }
}