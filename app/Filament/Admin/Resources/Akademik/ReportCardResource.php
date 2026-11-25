<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateReportCard;
use App\Filament\Admin\Resources\Akademik\Pages\EditReportCard;
use App\Filament\Admin\Resources\Akademik\Pages\ListReportCards;
use App\Filament\Admin\Resources\Akademik\Schemas\ReportCardForm;
use App\Filament\Admin\Resources\Akademik\Tables\ReportCardTable;
use App\Models\ReportCard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReportCardResource extends Resource
{
    protected static ?string $model = ReportCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Nilai Raport';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Standar Penilaian Raport';
    protected static ?string $pluralModelLabel = 'Standar Penilaian Raport';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return ReportCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportCardTable::configure($table);
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
            'index' => ListReportCards::route('/'),
            'create' => CreateReportCard::route('/create'),
            'edit' => EditReportCard::route('/{record}/edit'),
        ];
    }
}
