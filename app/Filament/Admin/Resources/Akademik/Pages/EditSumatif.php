<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumatifResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSumatif extends EditRecord
{
    protected static string $resource = SumatifResource::class;

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
