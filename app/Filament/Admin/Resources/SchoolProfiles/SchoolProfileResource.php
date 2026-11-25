<?php

namespace App\Filament\Admin\Resources\SchoolProfiles;

use App\Filament\Admin\Resources\SchoolProfiles\Pages;
use App\Filament\Admin\Resources\SchoolProfiles\Schemas\SchoolProfileForm;
use App\Filament\Admin\Resources\SchoolProfiles\Tables\SchoolProfilesTable;
use App\Models\SchoolProfile;
// use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use Filament\Support\Icons\Heroicon;



class SchoolProfileResource extends Resource
{
    protected static ?string $model = SchoolProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    // protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    // protected static ?string $navigationGroup = 'Manajemen Sekolah';

    protected static ?string $navigationLabel = 'Profil Sekolah';

    protected static ?string $modelLabel = 'Profil Sekolah';

    protected static ?string $pluralModelLabel = 'Profil Sekolah';

    protected static ?int $navigationSort = 1;

    /**
     * Helper untuk memeriksa apakah user yang login adalah Admin atau Kepala Sekolah (Headmaster)
     */
    private static function isAdminOrHeadmaster(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // 1. Cek dari relasi guru (acad_teachers -> role_type)
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

        // 2. Cek dari tabel user (role / type)
        $userRole = strtolower(trim((string) ($user->role ?? $user->type ?? '')));
        $fullRoleString = $userRole . ' ' . $teacherRole;

        // 3. Validasi hak akses khusus admin / headmaster
        return (
            str_contains($fullRoleString, 'headmaster') || 
            str_contains($fullRoleString, 'kepala') || 
            str_contains($fullRoleString, 'admin') || 
            str_contains($fullRoleString, 'super')
        );
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Sekolah';
    }

    public static function form(Schema $schema): Schema
    {
        return SchoolProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchoolProfiles::route('/'),
            'create' => Pages\CreateSchoolProfile::route('/create'),
            'edit' => Pages\EditSchoolProfile::route('/{record}/edit'),
        ];
    }

    // --- PEMBATASAN HAK AKSES CRUD (HANYA ADMIN & HEADMASTER) ---

    public static function shouldRegisterNavigation(): bool
    {
        return static::isAdminOrHeadmaster();
    }

    public static function canViewAny(): bool
    {
        return static::isAdminOrHeadmaster();
    }

    public static function canView(Model $record): bool
    {
        return static::isAdminOrHeadmaster();
    }

    public static function canCreate(): bool
    {
        return static::isAdminOrHeadmaster();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isAdminOrHeadmaster();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isAdminOrHeadmaster();
    }
}