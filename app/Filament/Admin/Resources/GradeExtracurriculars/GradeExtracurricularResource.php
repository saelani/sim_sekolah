<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars;

use App\Filament\Admin\Resources\GradeExtracurriculars\Pages\CreateGradeExtracurricular;
use App\Filament\Admin\Resources\GradeExtracurriculars\Pages\EditGradeExtracurricular;
use App\Filament\Admin\Resources\GradeExtracurriculars\Pages\ListGradeExtracurriculars;
use App\Filament\Admin\Resources\GradeExtracurriculars\Schemas\GradeExtracurricularForm;
use App\Filament\Admin\Resources\GradeExtracurriculars\Tables\GradeExtracurricularsTable;
use App\Models\GradeExtracurricular;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GradeExtracurricularResource extends Resource
{
    protected static ?string $model = GradeExtracurricular::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Kegiatan Ekstrakurikuler';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Kegiatan Ekstrakurikuler';
    protected static ?string $pluralModelLabel = 'Kegiatan Ekstrakurikuler';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }
    
    public static function form(Schema $schema): Schema
    {
        return GradeExtracurricularForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradeExtracurricularsTable::configure($table);
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
            'index' => ListGradeExtracurriculars::route('/'),
            'create' => CreateGradeExtracurricular::route('/create'),
            'edit' => EditGradeExtracurricular::route('/{record}/edit'),
        ];
    }
}
