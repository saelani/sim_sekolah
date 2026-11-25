<?php

namespace App\Filament\Admin\Resources\Exam\Questions\Pages;

use App\Filament\Admin\Resources\Exam\Questions\QuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
