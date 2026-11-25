<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateExtracurricularScore;
use App\Filament\Admin\Resources\Akademik\Pages\EditExtracurricularScore;
use App\Filament\Admin\Resources\Akademik\Pages\ListExtracurricularScores;
use App\Filament\Admin\Resources\Akademik\Schemas\ExtracurricularScoreForm;
use App\Filament\Admin\Resources\Akademik\Tables\ExtracurricularScoreTable;
use App\Filament\Admin\Resources\Akademik\Pages\BulkExtracurricularScore;
use App\Models\ExtracurricularScore;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExtracurricularScoreResource extends Resource
{
    protected static ?string $model = ExtracurricularScore::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Trophy;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Penilaian Ekstrakurikuler';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Penilaian Ekstrakurikuler';
    protected static ?string $pluralModelLabel = 'Penilaian Ekstrakurikuler';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return ExtracurricularScoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExtracurricularScoreTable::configure($table);
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
            'index' => ListExtracurricularScores::route('/'),
            'create' => CreateExtracurricularScore::route('/create'),
            'bulk-create' => BulkExtracurricularScore::route('/bulk-create'),
            'edit' => EditExtracurricularScore::route('/{record}/edit'),
        ];
    }
}
