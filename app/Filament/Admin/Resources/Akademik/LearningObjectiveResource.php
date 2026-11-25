<?php

namespace App\Filament\Admin\Resources\Akademik;

use App\Filament\Admin\Resources\Akademik\Pages\CreateLearningObjective;
use App\Filament\Admin\Resources\Akademik\Pages\EditLearningObjective;
use App\Filament\Admin\Resources\Akademik\Pages\ListLearningObjectives;
use App\Filament\Admin\Resources\Akademik\Schemas\LearningObjectiveForm;
use App\Filament\Admin\Resources\Akademik\Tables\LearningObjectiveTable;
use App\Models\LearningObjective;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder; // <-- Jangan lupa import ini
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LearningObjectiveResource extends Resource
{
    protected static ?string $model = LearningObjective::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::LightBulb;

    // 1. Mengubah Label Menu di Sidebar (Sub menu)
    protected static ?string $navigationLabel = 'Capaian Pembelajaran'; 

    // (Opsional) Mengubah judul/label halaman secara keseluruhan (breadcrumb, title page, dll)
    protected static ?string $modelLabel = 'Capaian Pembelajaran';
    protected static ?string $pluralModelLabel = 'Capaian Pembelajaran';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    /**
     * Membatasi data yang tampil di tabel berdasarkan guru yang login
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Jika user adalah Admin atau Headmaster, tampilkan semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return $query;
        }

        // Ambil ID dari relasi teacher milik user yang login
        $teacherId = optional($user->teacher()->first())->id;

        // Filter berdasarkan teacher_id langsung ATAU melalui relasi sumativeScope
        return $query->where(function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId)
            ->orWhereHas('sumativeScope', function ($subQuery) use ($teacherId) {
                $subQuery->where('teacher_id', $teacherId);
            });
        });
    }

    public static function form(Schema $schema): Schema
    {
        return LearningObjectiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LearningObjectiveTable::configure($table);
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
            'index' => ListLearningObjectives::route('/'),
            'create' => CreateLearningObjective::route('/create'),
            'edit' => EditLearningObjective::route('/{record}/edit'),
        ];
    }
}