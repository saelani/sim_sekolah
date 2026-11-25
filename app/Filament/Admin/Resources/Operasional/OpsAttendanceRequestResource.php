<?php

namespace App\Filament\Admin\Resources\Operasional;

use App\Models\OpsAttendanceRequest;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; 
use Filament\Tables\Table;   
use App\Filament\Admin\Resources\Operasional\Tables\OpsAttendanceRequestsTable;
use App\Filament\Admin\Resources\Operasional\Schemas\OpsAttendanceRequestForm;
use App\Filament\Admin\Resources\Operasional\Pages\ListOpsAttendanceRequests;
use App\Filament\Admin\Resources\Operasional\Pages\CreateOpsAttendanceRequest;
use App\Filament\Admin\Resources\Operasional\Pages\EditOpsAttendanceRequest;

class OpsAttendanceRequestResource extends Resource
{
    protected static ?string $model = OpsAttendanceRequest::class;
    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-document-text';
    }

    public static function getModelLabel(): string
    {
        return 'Request Absen';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Request Absen';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOpsAttendanceRequests::route('/'),
            'create' => CreateOpsAttendanceRequest::route('/create'),
            'edit' => EditOpsAttendanceRequest::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return OpsAttendanceRequestForm::configure($schema); 
    }

    public static function table(Table $table): Table
    {
        return OpsAttendanceRequestsTable::configure($table);
    }
}