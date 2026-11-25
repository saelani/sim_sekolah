<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsCashFlowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOpsCashFlows extends ListRecords
{
    protected static string $resource = OpsCashFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
