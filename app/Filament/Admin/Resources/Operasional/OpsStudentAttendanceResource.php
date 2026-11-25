<?php

namespace App\Filament\Admin\Resources\Operasional;

use App\Models\OpsStudentAttendance;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\Operasional\Tables\OpsStudentAttendancesTable;
use App\Filament\Admin\Resources\Operasional\Schemas\OpsStudentAttendanceForm;
use App\Filament\Admin\Resources\Operasional\Pages\ListOpsStudentAttendances;
use App\Filament\Admin\Resources\Operasional\Pages\CreateOpsStudentAttendance;
use App\Filament\Admin\Resources\Operasional\Pages\EditOpsStudentAttendance;
use App\Filament\Admin\Resources\Operasional\Pages\BulkOpsStudentAttendance;


class OpsStudentAttendanceResource extends Resource
{
    protected static ?string $model = OpsStudentAttendance::class;

    protected static ?int $navigationSort = 2;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getModelLabel(): string
    {
        return 'Absensi Siswa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Absensi Siswa';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOpsStudentAttendances::route('/'),
            'create' => CreateOpsStudentAttendance::route('/create'),
            'edit' => EditOpsStudentAttendance::route('/{record}/edit'),
            'bulk' => BulkOpsStudentAttendance::route('/bulk'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return OpsStudentAttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OpsStudentAttendancesTable::configure($table);
    }
}