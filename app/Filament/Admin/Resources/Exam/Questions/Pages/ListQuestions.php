<?php

namespace App\Filament\Admin\Resources\Exam\Questions\Pages;

use App\Filament\Admin\Resources\Exam\Questions\QuestionResource;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }
}
