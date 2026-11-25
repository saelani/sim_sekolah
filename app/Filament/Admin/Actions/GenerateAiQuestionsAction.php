<?php

namespace App\Filament\Admin\Actions;

use App\Models\Exam;
use App\Models\LearningObjective;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SumativeScope;
use Filament\Actions\Action;
use Filament\Actions\Action as FormAction;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateAiQuestionsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'generateAiQuestions';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Buat Soal dengan AI')
            ->icon('heroicon-o-sparkles')
            ->color('purple')
            ->modalHeading('✨ AI Question Generator (Kurikulum Merdeka)')
            ->modalDescription('Filter disesuaikan dengan jenis penilaian (Formatif, SLM, atau SAS).')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->schema([
                Section::make('1. Konfigurasi Jenis Penilaian & Scope')
                    ->schema([
                        Select::make('assessment_type')
                            ->label('Jenis Penilaian')
                            ->options([
                                'formatif' => 'Formatif (Tujuan Pembelajaran)',
                                'SLM'      => 'Sumatif Lingkup Materi / Bab (SLM)',
                                'SAS'      => 'Sumatif Akhir Semester (SAS)',
                            ])
                            ->default('formatif')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('learning_objective_id', null);
                                $set('sumative_scope_id', null);
                            }),

                        Grid::make(2)->schema([
                            Select::make('class_room_id')
                                ->label('Kelas Target')
                                ->options(fn () => SchoolClass::query()->pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('subject_id')
                                ->label('Mata Pelajaran')
                                ->options(fn () => Subject::query()->pluck('name', 'id')->toArray())
                                ->default(fn (?Exam $record) => $record?->subject_id)
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('learning_objective_id', null);
                                    $set('sumative_scope_id', null);
                                }),
                        ]),

                        Select::make('learning_objective_id')
                            ->label('Tujuan Pembelajaran (TP)')
                            ->options(function (Get $get) {
                                $subjectId = $get('subject_id');
                                if (! $subjectId) {
                                    return [];
                                }

                                return LearningObjective::query()
                                    ->where('subject_id', $subjectId)
                                    ->get()
                                    ->mapWithKeys(fn ($tp) => [
                                        $tp->id => "[{$tp->code}] " . Str::limit($tp->description, 60),
                                    ])
                                    ->toArray();
                            })
                            ->visible(fn (Get $get) => $get('assessment_type') === 'formatif')
                            ->disabled(fn (Get $get) => ! $get('subject_id'))
                            ->required(fn (Get $get) => $get('assessment_type') === 'formatif')
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('sumative_scope_id')
                            ->label('Lingkup Materi / Bab')
                            ->options(function (Get $get) {
                                $subjectId = $get('subject_id');
                                if (! $subjectId) {
                                    return [];
                                }

                                return SumativeScope::query()
                                    ->where('subject_id', $subjectId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->visible(fn (Get $get) => $get('assessment_type') === 'SLM')
                            ->disabled(fn (Get $get) => ! $get('subject_id'))
                            ->required(fn (Get $get) => $get('assessment_type') === 'SLM')
                            ->searchable()
                            ->preload()
                            ->live(),

                        Grid::make(2)->schema([
                            Select::make('bloom_taxonomy')
                                ->label('Tingkat Kesukaran (Taksonomi Bloom)')
                                ->options([
                                    'C1-C2' => 'LOTS (Mengingat & Memahami)',
                                    'C3-C4' => 'MOTS (Menerapkan & Menganalisis)',
                                    'C5-C6' => 'HOTS (Evaluasi & Kreasi)',
                                ])
                                ->default('C3-C4')
                                ->required(),

                            TextInput::make('question_count')
                                ->label('Jumlah Soal')
                                ->numeric()
                                ->default(5)
                                ->minValue(1)
                                ->maxValue(20)
                                ->required(),
                        ]),

                        ActionGroup::make([
                            FormAction::make('generatePromptText')
                                ->label('✨ Generate Prompt AI Disesuaikan')
                                ->button()
                                ->color('primary')
                                ->action(function (Get $get, Set $set) {
                                    $type = $get('assessment_type');
                                    $className = SchoolClass::find($get('class_room_id'))?->name ?? 'Kelas';
                                    $subjectName = Subject::find($get('subject_id'))?->name ?? 'Mata Pelajaran';
                                    $bloom = $get('bloom_taxonomy') ?? 'C3-C4';
                                    $jumlah = $get('question_count') ?? 5;

                                    $jenisLabel = match ($type) {
                                        'formatif' => 'Asesmen Formatif (Tujuan Pembelajaran)',
                                        'SLM'      => 'Asesmen Sumatif Lingkup Materi / Bab (SLM)',
                                        'SAS'      => 'Asesmen Sumatif Akhir Semester (SAS)',
                                        default    => 'Asesmen',
                                    };

                                    $scopeDetail = '';
                                    if ($type === 'formatif' && $get('learning_objective_id')) {
                                        $tp = LearningObjective::find($get('learning_objective_id'));
                                        if ($tp) {
                                            $scopeDetail = "Tujuan Pembelajaran (TP): [{$tp->code}] {$tp->description}\n";
                                        }
                                    } elseif ($type === 'SLM' && $get('sumative_scope_id')) {
                                        $scope = SumativeScope::find($get('sumative_scope_id'));
                                        if ($scope) {
                                            $scopeDetail = "Lingkup Materi / Bab: {$scope->name}\n";
                                        }
                                    }

                                    $prompt = "Buatkan {$jumlah} soal pilihan ganda (4 opsi: A, B, C, D) tingkat kesulitan {$bloom} untuk {$jenisLabel}.\n";
                                    $prompt .= "Mata Pelajaran: {$subjectName}\n";
                                    $prompt .= "Tingkat/Kelas: {$className}\n";
                                    if (! empty($scopeDetail)) {
                                        $prompt .= $scopeDetail;
                                    }

                                    $prompt .= "\nWAJIB BALAS HANYA DALAM FORMAT JSON MURNI TANPA TEKS TAMBAHAN DENGAN STRUKTUR BERIKUT:\n";
                                    $prompt .= "[\n";
                                    $prompt .= "  {\n";
                                    $prompt .= "    \"pertanyaan\": \"Teks soal di sini\",\n";
                                    $prompt .= "    \"opsi_a\": \"Jawaban A\",\n";
                                    $prompt .= "    \"opsi_b\": \"Jawaban B\",\n";
                                    $prompt .= "    \"opsi_c\": \"Jawaban C\",\n";
                                    $prompt .= "    \"opsi_d\": \"Jawaban D\",\n";
                                    $prompt .= "    \"kunci_jawaban\": \"A\"\n";
                                    $prompt .= "  }\n";
                                    $prompt .= ']';

                                    $set('generated_prompt', $prompt);
                                }),

                            FormAction::make('copyPromptText')
                                ->label('📋 Salin Prompt')
                                ->button()
                                ->color('success')
                                ->action(function (Get $get, $livewire) {
                                    $promptJson = json_encode($get('generated_prompt') ?? '');
                                    $livewire->js("
                                        const text = {$promptJson};
                                        if (text && text.trim() !== '') {
                                            navigator.clipboard.writeText(text).then(() => {
                                                new FilamentNotification()
                                                    .title('Prompt AI Berhasil Disalin!')
                                                    .success()
                                                    .send();
                                            });
                                        }
                                    ");
                                }),
                        ]),

                        Textarea::make('generated_prompt')
                            ->label('Hasil Generator Prompt')
                            ->rows(4)
                            ->readOnly(),
                    ]),

                Section::make('2. Import Hasil Jawaban JSON dari AI')
                    ->schema([
                        Textarea::make('ai_json_output')
                            ->label('Tempelkan Balasan JSON dari ChatGPT / Gemini')
                            ->placeholder("[\n  {\n    \"pertanyaan\": \"...\",\n    \"opsi_a\": \"...\",\n    ...\n  }\n]")
                            ->rows(5)
                            ->required(),

                        FormAction::make('saveQuestionsFromModal')
                            ->label('💾 Simpan Soal ke Database')
                            ->button()
                            ->color('success')
                            ->extraAttributes(['class' => 'w-full'])
                            ->action(function (Get $get, Exam $record, $livewire) {
                                $jsonText = $get('ai_json_output');

                                if (empty($jsonText)) {
                                    Notification::make()->title('JSON Masih Kosong')->warning()->send();

                                    return;
                                }

                                try {
                                    $questionsData = json_decode($jsonText, true, 512, JSON_THROW_ON_ERROR);

                                    if (! is_array($questionsData) || empty($questionsData)) {
                                        throw new \Exception('Format JSON tidak valid atau kosong.');
                                    }

                                    DB::transaction(function () use ($questionsData, $record) {
                                        foreach ($questionsData as $q) {
                                            $question = $record->questions()->create([
                                                'type'          => 'multiple_choice',
                                                'question_text' => $q['pertanyaan'] ?? $q['question'] ?? 'Soal CBT AI',
                                                'score_weight'  => 1.0,
                                            ]);

                                            $kunci = strtoupper(trim($q['kunci_jawaban'] ?? $q['correct_answer'] ?? 'A'));

                                            $optionsMap = [
                                                'A' => $q['opsi_a'] ?? $q['option_a'] ?? '',
                                                'B' => $q['opsi_b'] ?? $q['option_b'] ?? '',
                                                'C' => $q['opsi_c'] ?? $q['option_c'] ?? '',
                                                'D' => $q['opsi_d'] ?? $q['option_d'] ?? '',
                                            ];

                                            foreach ($optionsMap as $key => $text) {
                                                if (! empty($text)) {
                                                    $question->options()->create([
                                                        'option_text' => $text,
                                                        'is_correct'  => ($key === $kunci),
                                                    ]);
                                                }
                                            }
                                        }
                                    });

                                    Notification::make()
                                        ->title('Soal Berhasil Disimpan!')
                                        ->body(count($questionsData) . ' soal telah berhasil ditambahkan.')
                                        ->success()
                                        ->send();

                                    $livewire->dispatch('$refresh');
                                } catch (\Throwable $th) {
                                    Notification::make()
                                        ->title('Gagal Memproses Soal')
                                        ->body($th->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),
                    ]),
            ]);
    }
}