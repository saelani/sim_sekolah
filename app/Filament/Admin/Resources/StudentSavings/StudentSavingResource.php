<?php

namespace App\Filament\Admin\Resources\StudentSavings;

use App\Models\StudentSavings;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Import Schema, Table, dan Pages
use App\Filament\Admin\Resources\StudentSavings\Schemas\StudentSavingForm;
use App\Filament\Admin\Resources\StudentSavings\Tables\StudentSavingsTable;
use App\Filament\Admin\Resources\StudentSavings\Pages\ListStudentSavings;
use App\Filament\Admin\Resources\StudentSavings\Pages\CreateStudentSaving;
use App\Filament\Admin\Resources\StudentSavings\Pages\EditStudentSaving;
use App\Filament\Admin\Resources\StudentSavings\Pages\BulkStudentSaving;

class StudentSavingResource extends Resource
{
    protected static ?string $model = StudentSavings::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-wallet';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    protected static ?int $navigationSort = 3; 

    public static function getModelLabel(): string
    {
        return 'Tabungan Siswa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tabungan Siswa';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user && strtolower($user->role ?? '') === 'teacher') {
            $allowedClassIds = static::getTeacherAllowedClassIds();
            
            // Langsung filter berdasarkan class_id di tabel students
            $query->whereHas('student', function ($q) use ($allowedClassIds) {
                if (! empty($allowedClassIds)) {
                    $q->whereIn('class_id', $allowedClassIds);
                }
            });
        }

        return $query;
    }

    private static function getTeacherAllowedClassIds(): array
    {
        $user = Auth::user();

        if (! $user || strtolower($user->role ?? '') !== 'teacher') {
            return [];
        }

        $teacher = DB::table('acad_teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher?->id;

        if (! $teacherId) {
            return [];
        }

        $homeroomClassIds = DB::table('acad_classes')->where('teacher_id', $teacherId)->pluck('id')->toArray();
        $subjectClassIds  = DB::table('acad_teacher_subject_classes')->where('teacher_id', $teacherId)->pluck('class_id')->toArray();

        return array_unique(array_merge($homeroomClassIds, $subjectClassIds));
    }

    public static function form(Schema $schema): Schema
    {
        return StudentSavingForm::configure($schema);
    }

    

    public static function table(Table $table): Table
    {
        return StudentSavingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStudentSavings::route('/'),
            'create' => CreateStudentSaving::route('/create'),
            'bulk' => BulkStudentSaving::route('/bulk'),
            'edit'   => EditStudentSaving::route('/{record}/edit'),
        ];
    }
}