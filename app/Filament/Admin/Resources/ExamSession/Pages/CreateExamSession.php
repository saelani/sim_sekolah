<?php

namespace App\Filament\Admin\Resources\ExamSession\Pages;

use App\Filament\Admin\Resources\ExamSession\ExamSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExamSession extends CreateRecord
{
    protected static string $resource = ExamSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
