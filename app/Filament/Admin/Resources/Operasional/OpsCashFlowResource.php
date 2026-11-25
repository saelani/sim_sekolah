<?php

namespace App\Filament\Admin\Resources\Operasional;

use App\Models\OpsCashFlow;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\Operasional\Tables\OpsCashFlowsTable;
use App\Filament\Admin\Resources\Operasional\Schemas\OpsCashFlowForm;
use App\Filament\Admin\Resources\Operasional\Pages\ListOpsCashFlows;
use App\Filament\Admin\Resources\Operasional\Pages\CreateOpsCashFlow;
use App\Filament\Admin\Resources\Operasional\Pages\EditOpsCashFlow;

class OpsCashFlowResource extends Resource
{
    protected static ?string $model = OpsCashFlow::class;
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-banknotes';
    }

    public static function getModelLabel(): string
    {
        return 'Arus Kas';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Arus Kas';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOpsCashFlows::route('/'),
            'create' => CreateOpsCashFlow::route('/create'),
            'edit' => EditOpsCashFlow::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return OpsCashFlowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OpsCashFlowsTable::configure($table);
    }
}