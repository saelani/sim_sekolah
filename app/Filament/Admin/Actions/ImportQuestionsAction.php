<?php

namespace App\Filament\Admin\Actions;

use App\Models\Exam;
use Filament\Actions\Action;
use Filament\Actions\Action as FormAction;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\DB;

class ImportQuestionsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'importQuestions';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Impor Soal')
            ->icon('heroicon-o-document-arrow-up')
            ->color('success')
            ->modalHeading('Impor Soal dari JSON')
            ->modalDescription('Tempelkan balasan JSON soal, lalu klik Simpan untuk memasukkan ke database ujian ini.')
            ->schema([
                Textarea::make('ai_json_output')
                    ->label('JSON Soal')
                    ->placeholder("[{\"pertanyaan\":\"...\",\"opsi_a\":\"...\",\"opsi_b\":\"...\",\"opsi_c\":\"...\",\"opsi_d\":\"...\",\"kunci_jawaban\":\"A\"}]")
                    ->rows(8)
                    ->required(),

                FormAction::make('importNow')
                    ->label('💾 Simpan Soal ke Database')
                    ->button()
                    ->color('success')
                    ->extraAttributes(['class' => 'w-full'])
                    ->action(function (Get $get, Exam $record, $livewire) {
                        $this->importQuestions($get('ai_json_output'), $record);
                        $livewire->dispatch('$refresh');
                    }),
            ]);
    }

    protected function importQuestions(?string $jsonText, Exam $record): void
    {
        $raw = trim((string) $jsonText);

        if ($raw === '') {
            Notification::make()->title('JSON Masih Kosong')->warning()->send();

            return;
        }

        // Bersihkan pembungkus markdown ```json ... ``` jika ada
        $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $raw) ?? $raw;

        try {
            $questionsData = json_decode(trim($cleaned), true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($questionsData) || empty($questionsData)) {
                throw new \Exception('Format JSON tidak valid atau kosong.');
            }

            $imported = 0;

            DB::transaction(function () use ($questionsData, $record, &$imported) {
                foreach ($questionsData as $q) {
                    if (! is_array($q)) {
                        continue;
                    }

                    $questionText = trim((string) ($q['pertanyaan'] ?? $q['question'] ?? ''));
                    if ($questionText === '') {
                        continue;
                    }

                    $rawType = strtolower((string) ($q['tipe'] ?? $q['type'] ?? 'multiple_choice'));
                    $type = in_array($rawType, ['essay', 'uraian'], true) ? 'essay' : 'multiple_choice';

                    $question = $record->questions()->create([
                        'type'          => $type,
                        'question_text' => $questionText,
                        'score_weight'  => (float) ($q['bobot'] ?? $q['score_weight'] ?? 1.0),
                    ]);

                    if ($type === 'multiple_choice') {
                        $kunci = strtoupper(trim((string) ($q['kunci_jawaban'] ?? $q['correct_answer'] ?? 'A')));

                        $optionsMap = array_filter([
                            'A' => $q['opsi_a'] ?? $q['option_a'] ?? null,
                            'B' => $q['opsi_b'] ?? $q['option_b'] ?? null,
                            'C' => $q['opsi_c'] ?? $q['option_c'] ?? null,
                            'D' => $q['opsi_d'] ?? $q['option_d'] ?? null,
                            'E' => $q['opsi_e'] ?? $q['option_e'] ?? null,
                        ], fn ($val) => filled($val));

                        if (! empty($optionsMap)) {
                            foreach ($optionsMap as $key => $text) {
                                $question->options()->create([
                                    'option_text' => (string) $text,
                                    'is_correct'  => ($key === $kunci),
                                ]);
                            }
                        } elseif (isset($q['options']) && is_array($q['options'])) {
                            foreach ($q['options'] as $key => $opt) {
                                if (is_array($opt)) {
                                    $optText = $opt['option_text'] ?? $opt['text'] ?? $opt['label'] ?? null;
                                    if (! filled($optText)) {
                                        continue;
                                    }
                                    $optKey = strtoupper((string) ($opt['key'] ?? $opt['code'] ?? $key));
                                    $isCorrect = (bool) ($opt['is_correct'] ?? ($optKey === $kunci));

                                    $question->options()->create([
                                        'option_text' => (string) $optText,
                                        'is_correct'  => $isCorrect,
                                    ]);
                                } elseif (filled($opt)) {
                                    $optKey = is_string($key) ? strtoupper($key) : chr(65 + (int) $key);
                                    $question->options()->create([
                                        'option_text' => (string) $opt,
                                        'is_correct'  => ($optKey === $kunci),
                                    ]);
                                }
                            }
                        }
                    }

                    $imported++;
                }
            });

            Notification::make()
                ->title('Impor Berhasil')
                ->body("{$imported} soal telah berhasil ditambahkan ke ujian: {$record->title}")
                ->success()
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Gagal Impor Soal')
                ->body('Terjadi kesalahan: ' . $th->getMessage())
                ->danger()
                ->send();
        }
    }
}