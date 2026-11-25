<?php

namespace App\Filament\Admin\Resources\Materials;

use App\Filament\Admin\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Admin\Resources\Materials\Pages\EditMaterial;
use App\Filament\Admin\Resources\Materials\Pages\ListMaterials;
use App\Filament\Admin\Resources\Materials\Schemas\MaterialForm;
use App\Filament\Admin\Resources\Materials\Tables\MaterialsTable;
use App\Models\Material;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder; // <-- Jangan lupa import ini
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Materi Belajar'; 

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Materi Belajar';
    protected static ?string $pluralModelLabel = 'Materi Belajar';

    protected static ?int $navigationSort = 7;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    /**
     * Membatasi data materi belajar berdasarkan user yang login
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user adalah Admin atau Headmaster, tampilkan semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return $query;
        }

        // Jika user biasa, filter berdasarkan user_id miliknya
        return $query->where('user_id', $user->id);
    }
    
    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}