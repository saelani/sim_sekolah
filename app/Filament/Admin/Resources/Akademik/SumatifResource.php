<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateSumatif;
use App\Filament\Admin\Resources\Akademik\Pages\EditSumatif;
use App\Filament\Admin\Resources\Akademik\Pages\ListSumatifs;
use App\Filament\Admin\Resources\Akademik\Schemas\SumatifForm;
use App\Filament\Admin\Resources\Akademik\Tables\SumatifTable;
use App\Filament\Admin\Resources\Akademik\Pages\BulkSumatifByScope;
use App\Filament\Admin\Resources\Akademik\Pages\BulkSumativeScore; // Import page Anda

use App\Models\Sumatif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SumatifResource extends Resource
{
    protected static ?string $model = Sumatif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clipboard;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Penilaian Sumatif';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Standar Penilaian Sumatif';
    protected static ?string $pluralModelLabel = 'Standar Penilaian Sumatif';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return SumatifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SumatifTable::configure($table);
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
            'index' => ListSumatifs::route('/'),
            'create' => CreateSumatif::route('/create'),
            'bulk-by-scope' => BulkSumatifByScope::route('/bulk-by-scope'),
            // Daftarkan page kustom Anda di sini
            'bulk-score' => BulkSumativeScore::route('/bulk-score'),
            'edit' => EditSumatif::route('/{record}/edit'),
        ];
    }
}
