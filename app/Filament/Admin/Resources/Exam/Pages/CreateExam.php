<?php

namespace App\Filament\Admin\Resources\Exam\Pages;

use App\Filament\Admin\Resources\Exam\ExamResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExam extends CreateRecord
{
    protected static string $resource = ExamResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
