<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateP5Project;
use App\Filament\Admin\Resources\Akademik\Pages\EditP5Project;
use App\Filament\Admin\Resources\Akademik\Pages\ListP5Projects;
use App\Filament\Admin\Resources\Akademik\Schemas\P5ProjectForm;
use App\Filament\Admin\Resources\Akademik\Tables\P5ProjectTable;
use App\Models\P5Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class P5ProjectResource extends Resource
{
    protected static ?string $model = P5Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Standar Penilaian P5 Project';

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Standar Penilaian P5 Project';
    protected static ?string $pluralModelLabel = 'Standar Penilaian P5 Project';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Pengelolaan Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return P5ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return P5ProjectTable::configure($table);
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
            'index' => ListP5Projects::route('/'),
            'create' => CreateP5Project::route('/create'),
            'edit' => EditP5Project::route('/{record}/edit'),
        ];
    }
}
