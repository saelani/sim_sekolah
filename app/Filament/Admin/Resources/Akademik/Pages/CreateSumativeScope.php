<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumativeScopeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSumativeScope extends CreateRecord
{
    protected static string $resource = SumativeScopeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // Jika bukan admin/headmaster, otomatis set teacher_id berdasarkan guru yang login
        if (!($user->role === 'admin' || $user->role === 'headmaster')) {
            $data['teacher_id'] = optional($user->teacher()->first())->id;
        }

        return $data;
    }
    
}
