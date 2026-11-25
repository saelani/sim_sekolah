<?php

namespace App\Filament\Admin\Resources\ExamSession;

use App\Filament\Admin\Resources\ExamSession\Pages\CreateExamSession;
use App\Filament\Admin\Resources\ExamSession\Pages\EditExamSession;
use App\Filament\Admin\Resources\ExamSession\Pages\ListExamSessions;
use App\Filament\Admin\Resources\ExamSession\Schemas\ExamSessionForm;
use App\Filament\Admin\Resources\ExamSession\Tables\ExamSessionTable;
use App\Models\ExamSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExamSessionResource extends Resource
{
    protected static ?string $model = ExamSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Hasil & Sesi Ujian';

    protected static ?string $modelLabel = 'Sesi Ujian';

    protected static ?string $pluralModelLabel = 'Daftar Sesi Ujian';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen CBT & Nilai';
    }

    public static function form(Schema $schema): Schema
    {
        return ExamSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamSessionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamSessions::route('/'),
            'create' => CreateExamSession::route('/create'),
            'edit' => EditExamSession::route('/{record}/edit'),
        ];
    }
}
