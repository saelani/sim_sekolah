<?php

namespace App\Filament\Admin\Resources\Exam;

use App\Filament\Admin\Resources\Exam\Pages\CreateExam;
use App\Filament\Admin\Resources\Exam\Pages\EditExam;
use App\Filament\Admin\Resources\Exam\Pages\ListExams;
use App\Filament\Admin\Resources\Exam\Schemas\ExamForm;
use App\Filament\Admin\Resources\Exam\Tables\ExamTable;
use App\Models\Exam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'Ujian CBT';

    protected static ?string $modelLabel = 'Ujian CBT';

    protected static ?string $pluralModelLabel = 'Daftar Ujian CBT';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen CBT & Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return ExamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListExams::route('/'),
            'create' => CreateExam::route('/create'),
            'edit'   => EditExam::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (in_array($user->role, ['admin', 'headmaster'], true)) {
            return $query;
        }

        if ($user->role === 'teacher') {
            $teacherId = static::resolveTeacherId($user);

            if (! $teacherId) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where('teacher_id', $teacherId);
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        return (bool) $user && in_array($user->role, ['admin', 'headmaster', 'teacher'], true);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        return (bool) $user && in_array($user->role, ['admin', 'headmaster', 'teacher'], true);
    }

    public static function canView(Model $record): bool
    {
        return static::ownsRecordOrPrivileged($record);
    }

    public static function canEdit(Model $record): bool
    {
        return static::ownsRecordOrPrivileged($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::ownsRecordOrPrivileged($record);
    }

    protected static function ownsRecordOrPrivileged(Model $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (in_array($user->role, ['admin', 'headmaster'], true)) {
            return true;
        }

        if ($user->role === 'teacher') {
            $teacherId = static::resolveTeacherId($user);

            return $teacherId !== null && (int) $record->teacher_id === $teacherId;
        }

        return false;
    }

    protected static function resolveTeacherId(object $user): ?int
    {
        if (method_exists($user, 'resolveTeacher')) {
            $teacher = $user->resolveTeacher();

            return $teacher?->id !== null ? (int) $teacher->id : null;
        }

        $teacherId = $user->teacher?->id ?? $user->teacherByUserId?->id ?? null;

        return $teacherId !== null ? (int) $teacherId : null;
    }

    public static function getRelations(): array
    {
        return [];
    }
}