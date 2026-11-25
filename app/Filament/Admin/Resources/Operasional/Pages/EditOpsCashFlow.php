<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsCashFlowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOpsCashFlow extends EditRecord
{
    protected static string $resource = OpsCashFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
