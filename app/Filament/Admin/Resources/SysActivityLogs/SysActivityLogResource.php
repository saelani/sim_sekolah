<?php

namespace App\Filament\Admin\Resources\SysActivityLogs;

use App\Filament\Admin\Resources\SysActivityLogs\Pages\CreateSysActivityLog;
use App\Filament\Admin\Resources\SysActivityLogs\Pages\EditSysActivityLog;
use App\Filament\Admin\Resources\SysActivityLogs\Pages\ListSysActivityLogs;
use App\Filament\Admin\Resources\SysActivityLogs\Schemas\SysActivityLogForm;
use App\Filament\Admin\Resources\SysActivityLogs\Tables\SysActivityLogsTable;
use App\Models\SysActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SysActivityLogResource extends Resource
{
    protected static ?string $model = SysActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    public static function form(Schema $schema): Schema
    {
        return SysActivityLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SysActivityLogsTable::configure($table);
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
            'index' => ListSysActivityLogs::route('/'),
            'create' => CreateSysActivityLog::route('/create'),
            'edit' => EditSysActivityLog::route('/{record}/edit'),
        ];
    }
}
