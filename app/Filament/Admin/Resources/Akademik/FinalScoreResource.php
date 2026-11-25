<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateFinalScore;
use App\Filament\Admin\Resources\Akademik\Pages\EditFinalScore;
use App\Filament\Admin\Resources\Akademik\Pages\ListFinalScores;
use App\Filament\Admin\Resources\Akademik\Schemas\FinalScoreForm;
use App\Filament\Admin\Resources\Akademik\Tables\FinalScoreTable;
use App\Models\FinalScore;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum; // <-- Tambahkan use BackedEnum ini

class FinalScoreResource extends Resource
{
    protected static ?string $model = FinalScore::class;

    // Ubah deklarasi menjadi BackedEnum|string|null (tanpa tanda tanya di depan string)
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Penilaian Akhir';

    protected static ?string $modelLabel = 'Nilai Akhir Rapor';
    protected static ?string $pluralModelLabel = 'Nilai Akhir Rapor';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return FinalScoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinalScoreTable::configure($table);
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
            'index' => ListFinalScores::route('/'),
            'create' => CreateFinalScore::route('/create'),
            'edit' => EditFinalScore::route('/{record}/edit'),
        ];
    }
}