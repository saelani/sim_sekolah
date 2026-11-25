<?php

namespace App\Filament\Admin\Resources\Materials\Schemas;

use App\Models\SchoolClass;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
// use Filament\Forms;
// use Filament\Schemas\Schema;
// use Illuminate\Support\Facades\Auth;

class MaterialForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('learning_objective_id', null))
                    ->label('Mapel'),

                Forms\Components\Select::make('learning_objective_id')
                    ->relationship(
                        name: 'learningObjective',
                        titleAttribute: 'description',
                        modifyQueryUsing: function (Builder $query, Get $get): Builder {
                            $subjectId = $get('subject_id');

                            if (! $subjectId) {
                                return $query->whereRaw('1 = 0');
                            }

                            // Jika kolom di tabel learning objectives adalah subject_id:
                            return $query->where('subject_id', $subjectId);

                            // Jika ingin lewat relasi di LearningObjective (misal relasi bernama `subject`):
                            // return $query->whereHas('subject', fn (Builder $q) => $q->whereKey($subjectId));
                        }
                    )
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->label('Learning Objective'),
               

                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    // ->options(fn () => SchoolClass::query()->orderBy('name')->pluck('name', 'id'))
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->label('Kelas'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('slug', Str::slug((string) $state)))
                    ->label('Judul'),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Slug'),
                    
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(fn () => Auth::id())
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->label('Pembuat'),

                Forms\Components\Select::make('type')
                    ->options([
                        'text'         => 'Text',
                        'pdf'          => 'PDF',
                        'image'        => 'Image',
                        'link_youtube' => 'Link YouTube',
                    ])
                    ->nullable()
                    ->searchable()
                    ->label('Tipe'),

                Forms\Components\Textarea::make('content')
                    ->rows(8)
                    ->label('Konten'),

                // Forms\Components\TextInput::make('file_path')
                //     ->nullable()
                //     ->maxLength(255)
                //     ->label('Path File'),

                Forms\Components\Select::make('type')
                    ->options([
                        'text'         => 'Text',
                        'pdf'          => 'PDF',
                        'image'        => 'Image',
                        'link_youtube' => 'Link YouTube',
                    ])
                    ->nullable()
                    ->searchable()
                    ->live()
                    ->label('Tipe'),

                Forms\Components\FileUpload::make('file_path')
                    ->label('File Materi (Gambar / PDF)')
                    ->disk('public')
                    ->directory('materials')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable()
                    ->nullable()
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['pdf', 'image'], true)),

                Forms\Components\TextInput::make('external_url')
                    ->nullable()
                    ->maxLength(255)
                    ->label('External URL'),

                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->label('Aktif'),
            ]);
    }
}