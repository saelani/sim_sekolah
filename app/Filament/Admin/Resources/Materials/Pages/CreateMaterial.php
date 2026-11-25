<?php

namespace App\Filament\Admin\Resources\Materials\Pages;

use App\Filament\Admin\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // Jika bukan admin/headmaster, otomatis isi user_id dengan ID user yang login
        if (!($user->role === 'admin' || $user->role === 'headmaster')) {
            $data['user_id'] = $user->id;
        }

        return $data;
    }
}
