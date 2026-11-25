<?php

namespace App\Filament\Admin\Resources\Exam\Questions;

use App\Filament\Admin\Resources\Exam\Questions\Pages\CreateQuestion;
use App\Filament\Admin\Resources\Exam\Questions\Pages\EditQuestion;
use App\Filament\Admin\Resources\Exam\Questions\Pages\ListQuestions;
use App\Filament\Admin\Resources\Exam\Questions\Schemas\QuestionForm;
use App\Filament\Admin\Resources\Exam\Questions\Tables\QuestionTable;
use App\Models\Exam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = \App\Models\Question::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $navigationLabel = 'Soal CBT';

    protected static ?string $modelLabel = 'Soal CBT';

    protected static ?string $pluralModelLabel = 'Daftar Soal CBT';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen CBT & Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return QuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestions::route('/'),
            'create' => CreateQuestion::route('/create'),
            'edit' => EditQuestion::route('/{record}/edit'),
        ];
    }
}
