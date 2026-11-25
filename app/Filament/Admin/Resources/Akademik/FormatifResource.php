<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateFormatif;
use App\Filament\Admin\Resources\Akademik\Pages\EditFormatif;
use App\Filament\Admin\Resources\Akademik\Pages\ListFormatifs;
use App\Filament\Admin\Resources\Akademik\Schemas\FormatifForm;
use App\Filament\Admin\Resources\Akademik\Tables\FormatifTable;
use App\Filament\Admin\Resources\Akademik\Pages\BulkFormatifByTp;
use App\Models\Formatif;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormatifResource extends Resource
{
    protected static ?string $model = Formatif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Standar Penilaian Formatif';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Standar Penilaian Formatif';
    protected static ?string $pluralModelLabel = 'Standar Penilaian Formatif';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return FormatifForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormatifTable::configure($table);
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
            'index' => ListFormatifs::route('/'),
            'create' => CreateFormatif::route('/create'),
            'edit' => EditFormatif::route('/{record}/edit'),
            'bulk-by-tp' => BulkFormatifByTp::route('/bulk-by-tp'),
        ];
    }
}
