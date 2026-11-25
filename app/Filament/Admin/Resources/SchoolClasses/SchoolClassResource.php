<?php

namespace App\Filament\Admin\Resources\SchoolClasses;

use App\Filament\Admin\Resources\SchoolClasses\Pages\CreateSchoolClass;
use App\Filament\Admin\Resources\SchoolClasses\Pages\EditSchoolClass;
use App\Filament\Admin\Resources\SchoolClasses\Pages\ListSchoolClasses;
use App\Filament\Admin\Resources\SchoolClasses\Schemas\SchoolClassForm;
use App\Filament\Admin\Resources\SchoolClasses\Tables\SchoolClassesTable;
use App\Models\SchoolClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Kelas'; 

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Kelas';
    }
    
    public static function form(Schema $schema): Schema
    {
        return SchoolClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolClassesTable::configure($table);
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
            'index' => ListSchoolClasses::route('/'),
            'create' => CreateSchoolClass::route('/create'),
            'edit' => EditSchoolClass::route('/{record}/edit'),
        ];
    }
}
