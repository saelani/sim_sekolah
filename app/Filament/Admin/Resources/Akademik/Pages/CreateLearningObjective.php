<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\LearningObjectiveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningObjective extends CreateRecord
{
    protected static string $resource = LearningObjectiveResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

  
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        if (!($user->role === 'admin' || $user->role === 'headmaster')) {
            $data['teacher_id'] = optional($user->teacher()->first())->id;
        }

        return $data;
    }
}
