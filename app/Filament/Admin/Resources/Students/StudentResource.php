<?php

namespace App\Filament\Admin\Resources\Students;

use App\Filament\Admin\Resources\Students\Pages\CreateStudent;
use App\Filament\Admin\Resources\Students\Pages\EditStudent;
use App\Filament\Admin\Resources\Students\Pages\ListStudents;
use App\Filament\Admin\Resources\Students\Schemas\StudentForm;
use App\Filament\Admin\Resources\Students\Tables\StudentsTable;
use App\Models\SchoolProfile;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    /** Dipakai SchoolProfileResource: pangkatan hanya Admin & Headmaster */
    private static function isAdminOrHeadmaster(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        $teacherRole = '';
        if (!empty($user->teacher_id)) {
            $teacher = \App\Models\Teacher::find($user->teacher_id);
            if ($teacher && !empty($teacher->role_type)) {
                $teacherRole = strtolower(trim((string) $teacher->role_type));
            }
        }

        if (empty($teacherRole)) {
            $teacherByUserId = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacherByUserId && !empty($teacherByUserId->role_type)) {
                $teacherRole = strtolower(trim((string) $teacherByUserId->role_type));
            }
        }

        $userRole = strtolower(trim((string) ($user->role ?? $user->type ?? '')));
        $fullRoleString = $userRole . ' ' . $teacherRole;

        return (
            str_contains($fullRoleString, 'headmaster') ||
            str_contains($fullRoleString, 'kepala') ||
            str_contains($fullRoleString, 'admin') ||
            str_contains($fullRoleString, 'super')
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true; // semua user (admin/guru) tampil di menu
    }

    public static function canViewAny(): bool
    {
        return true; // semua user bisa lihat daftar siswa
    }

    public static function canView(Model $record): bool
    {
        return true; // semua user bisa lihat detail siswa
    }

    public static function canCreate(): bool
    {
        return static::isAdminOrHeadmaster(); // admin saja
    }

    public static function canEdit(Model $record): bool
    {
        return static::isAdminOrHeadmaster(); // admin saja
    }

    public static function canDelete(Model $record): bool
    {
        return static::isAdminOrHeadmaster(); // admin saja
    }

    public static function getSchoolProfile(): ?SchoolProfile
    {
        return SchoolProfile::first();
    }

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
