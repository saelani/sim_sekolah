<?php

namespace App\Filament\Admin\Resources\Exam\Questions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Soal')
                    ->schema([
                        Select::make('cbt_exam_id')
                            ->label('Ujian CBT')
                            ->relationship('exam', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('type')
                            ->label('Tipe Soal')
                            ->options([
                                'single_choice'   => 'Pilihan Tunggal',
                                'multiple_choice' => 'Pilihan Ganda',
                                'short_answer'    => 'Isian Singkat',
                                'essay'           => 'Uraian / Essay',
                            ])
                            ->required(),

                        Textarea::make('question_text')
                            ->label('Isi Soal')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        FileUpload::make('media_path')
                            ->label('Media (Gambar / Video)')
                            ->disk('public')
                            ->directory('cbt-media')
                            ->visibility('public'),

                        TextInput::make('score_weight')
                            ->label('Bobot Skor')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),
                    ]),
            ]);
    }
}