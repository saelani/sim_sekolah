<?php

namespace App\Filament\Admin\Resources\TeacherSubjectClasses;

use App\Filament\Admin\Resources\TeacherSubjectClasses\Pages\CreateTeacherSubjectClass;
use App\Filament\Admin\Resources\TeacherSubjectClasses\Pages\EditTeacherSubjectClass;
use App\Filament\Admin\Resources\TeacherSubjectClasses\Pages\ListTeacherSubjectClasses;
use App\Filament\Admin\Resources\TeacherSubjectClasses\Schemas\TeacherSubjectClassForm;
use App\Filament\Admin\Resources\TeacherSubjectClasses\Tables\TeacherSubjectClassesTable;
use App\Models\TeacherSubjectClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeacherSubjectClassResource extends Resource
{
    protected static ?string $model = TeacherSubjectClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Pembagian Guru';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Pembagian Guru';
    protected static ?string $pluralModelLabel = 'Pembagian Guru';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Kelas';
    }
    
    public static function form(Schema $schema): Schema
    {
        return TeacherSubjectClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherSubjectClassesTable::configure($table);
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
            'index' => ListTeacherSubjectClasses::route('/'),
            'create' => CreateTeacherSubjectClass::route('/create'),
            'edit' => EditTeacherSubjectClass::route('/{record}/edit'),
        ];
    }
}
