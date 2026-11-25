<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateSumativeScope;
use App\Filament\Admin\Resources\Akademik\Pages\EditSumativeScope;
use App\Filament\Admin\Resources\Akademik\Pages\ListSumativeScopes;
use App\Filament\Admin\Resources\Akademik\Schemas\SumativeScopeForm;
use App\Filament\Admin\Resources\Akademik\Tables\SumativeScopeTable;
use App\Models\SumativeScope;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder; // <-- Jangan lupa import ini
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SumativeScopeResource extends Resource
{
    protected static ?string $model = SumativeScope::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clipboard;

    protected static ?string $navigationLabel = 'Lingkup Materi'; 

    protected static ?string $modelLabel = 'Lingkup Materi';
    protected static ?string $pluralModelLabel = 'Lingkup Materi';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    /**
     * Membatasi data yang tampil di tabel berdasarkan user yang login
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user adalah Admin atau Headmaster, tampilkan semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return $query;
        }

        // Ambil ID dari relasi teacher milik user yang login (karena hasMany, kita ambil yang pertama)
        $teacherId = optional($user->teacher()->first())->id;

        // Jika user tidak punya data teacher sama sekali, berikan ID 0 agar tidak menampilkan data orang lain
        return $query->where('teacher_id', $teacherId ?? 0);
    }

    public static function form(Schema $schema): Schema
    {
        return SumativeScopeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SumativeScopeTable::configure($table);
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
            'index' => ListSumativeScopes::route('/'),
            'create' => CreateSumativeScope::route('/create'),
            'edit' => EditSumativeScope::route('/{record}/edit'),
        ];
    }
}