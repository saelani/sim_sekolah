<?php

namespace App\Filament\Admin\Resources\Exam\Pages;

use App\Filament\Admin\Resources\Exam\ExamResource;
use Filament\Resources\Pages\EditRecord;

class EditExam extends EditRecord
{
    protected static string $resource = ExamResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
