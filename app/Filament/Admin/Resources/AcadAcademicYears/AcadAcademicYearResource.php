<?php

namespace App\Filament\Admin\Resources\AcadAcademicYears;

use App\Filament\Admin\Resources\AcadAcademicYears\Pages\CreateAcadAcademicYear;
use App\Filament\Admin\Resources\AcadAcademicYears\Pages\EditAcadAcademicYear;
use App\Filament\Admin\Resources\AcadAcademicYears\Pages\ListAcadAcademicYears;
use App\Filament\Admin\Resources\AcadAcademicYears\Schemas\AcadAcademicYearForm;
use App\Filament\Admin\Resources\AcadAcademicYears\Tables\AcadAcademicYearsTable;
use App\Models\AcademicYear;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcadAcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Tahun Ajaran'; 

    // Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Tahun Ajaran';
    protected static ?string $pluralModelLabel = 'Tahun Ajaran';

    // Mengatur urutan sub-menu (angka kecil tampil lebih atas)
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }
    
    public static function form(Schema $schema): Schema
    {
        return AcadAcademicYearForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcadAcademicYearsTable::configure($table);
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
            'index' => ListAcadAcademicYears::route('/'),
            'create' => CreateAcadAcademicYear::route('/create'),
            'edit' => EditAcadAcademicYear::route('/{record}/edit'),
        ];
    }
}