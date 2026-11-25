<?php

namespace App\Filament\Admin\Pages\ToolsAI;

use App\Models\SchoolClass;
use App\Models\LearningObjective;
use App\Models\Subject;
use App\Models\SumativeScope;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class PromptGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-sparkles';

    protected static string | UnitEnum | null $navigationGroup = 'Tools AI';

    protected static ?string $title = 'Generator Bahan Ajar & Soal AI';

    protected string $view = 'filament.admin.pages.tools-a-i.prompt-generator';

    public ?array $data = [];
    public string $mode = 'presentasi'; // Opsi mode: presentasi, soal, bahan, modul, rubrik
    public string $generatedPrompt = '';

    /**
     * Mengatur lebar halaman di Filament v4
     */
    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->form->fill([
            'phase' => 'C',
            'level' => 5,
            'assessment_type' => 'sumatif_lingkup',
            'cognitive' => 'Seimbang (LOTS & HOTS)',
            'jml_pg' => 10,
            'jml_isian' => 5,
            'jml_essay' => 5,
            'max_slides' => 10,
            'model' => 'Inkuiri-Discovery Learning',
            'duration' => '2 x 35 menit',
        ]);

        $this->generatePrompt();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Konfigurasi Generator Prompt AI')
                    ->schema([
                        // 1. Opsi Jenis Asesmen / Soal
                        Select::make('assessment_type')
                            ->label('Jenis Asesmen / Soal')
                            ->options([
                                'formatif' => '1. Asesmen Formatif (Proses / Harian)',
                                'sumatif_lingkup' => '2. Asesmen Sumatif Lingkup Materi (Bab)',
                                'sumatif_sas' => '3. Asesmen Sumatif Akhir Semester (SAS / PAS)',
                                'remedial' => '4. Soal Remedial & Pengayaan (Diferensiasi)',
                                'proyek' => '5. Asesmen Kokurikuler (P5 / Projek Profil)',
                                'akm' => '6. Soal AKM (Literasi & Numerasi SD)',
                            ])
                            ->visible(fn () => $this->mode === 'soal')
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 2. Pilih Mata Pelajaran
                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(function () {
                                $user = Auth::user();
                                $query = Subject::query();

                                if ($user && ($user->role ?? '') === 'teacher') {
                                    $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
                                    if ($teacher) {
                                        $query->whereHas('teacherSubjectClasses', fn ($q) => 
                                            $q->where('teacher_id', $teacher->id)
                                        );
                                    }
                                }

                                return $query->orderBy('id', 'asc')->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default(fn () => session('last_tp_subject_id'))
                            ->afterStateUpdated(function (Set $set) {
                                $set('sumative_scope_id', null);
                                $set('learning_objective_id', null);
                                $set('learning_objective', null);
                                $this->generatePrompt();
                            })
                            ->required(),

                        // 3. Pilih Bab / Lingkup Materi
                        Select::make('sumative_scope_id')
                            ->label('Bab / Lingkup Materi')
                            ->options(function (Get $get) {
                                $subjectId = $get('subject_id');
                                if (! $subjectId) {
                                    return [];
                                }
                                return SumativeScope::query()
                                    ->where('subject_id', $subjectId)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default(fn () => session('last_tp_sumative_scope_id'))
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('learning_objective_id', null);

                                if ($state) {
                                    $scope = SumativeScope::find($state);
                                    if ($scope) {
                                        $set('phase', $scope->phase);
                                        $set('semester', (string) $scope->semester);
                                    }

                                    $tps = LearningObjective::where('sumative_scope_id', $state)
                                        ->pluck('description')
                                        ->toArray();

                                    if (! empty($tps)) {
                                        $formattedTps = array_map(fn ($tp) => "- {$tp}", $tps);
                                        $set('learning_objective', implode("\n", $formattedTps));
                                    } else {
                                        $set('learning_objective', null);
                                    }
                                } else {
                                    $set('learning_objective', null);
                                }

                                $this->generatePrompt();
                            }),

                        // 4. Tingkat Kelas
                        Select::make('level')
                            ->label('Tingkat Kelas')
                            ->options(
                                SchoolClass::query()
                                    ->select('level')
                                    ->distinct()
                                    ->orderBy('level')
                                    ->pluck('level', 'level')
                                    ->map(fn ($level) => "Kelas {$level}")
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default(fn () => session('last_tp_level'))
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    if (in_array($state, [1, 2])) {
                                        $set('phase', 'A');
                                    } elseif (in_array($state, [3, 4])) {
                                        $set('phase', 'B');
                                    } elseif (in_array($state, [5, 6])) {
                                        $set('phase', 'C');
                                    }
                                }
                                $this->generatePrompt();
                            })
                            ->required(),

                        // 5. Fase Kurikulum
                        Select::make('phase')
                            ->label('Fase Kurikulum')
                            ->options([
                                'A' => 'Fase A (Kelas 1-2)',
                                'B' => 'Fase B (Kelas 3-4)',
                                'C' => 'Fase C (Kelas 5-6)',
                            ])
                            ->default(fn () => session('last_tp_phase'))
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt())
                            ->required(),

                        // 6. Jumlah Maksimal Slide
                        TextInput::make('max_slides')
                            ->label('Maksimal Jumlah Slide')
                            ->numeric()
                            ->default(10)
                            ->minValue(3)
                            ->maxValue(10)
                            ->visible(fn () => $this->mode === 'presentasi')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 7. Dominasi Level Kognitif
                        Select::make('cognitive')
                            ->label('Dominasi Level Kognitif')
                            ->options([
                                'Seimbang (LOTS & HOTS)' => 'Seimbang (C1 s.d C5)',
                                'Dominan HOTS (C4-C6)' => 'Dominan HOTS (Analisis, Evaluasi, Kreasi)',
                                'Dominan LOTS/MOTS (C1-C3)' => 'Dominan Dasar (Mengingat & Memahami)',
                            ])
                            ->visible(fn () => $this->mode === 'soal')
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 8. Model Pedagogi
                        Select::make('model')
                            ->label('Model Pedagogi')
                            ->options([
                                'Inkuiri-Discovery Learning' => 'Inkuiri-Discovery Learning',
                                'Project Based Learning (PjBL)' => 'Project Based Learning (PjBL)',
                                'Problem Based Learning (PBL)' => 'Problem Based Learning (PBL)',
                                'Deep Learning (Mindful, Meaningful, Joyful)' => 'Deep Learning (Mindful, Meaningful, Joyful)',
                                'Cooperative Learning' => 'Cooperative Learning',
                            ])
                            ->visible(fn () => in_array($this->mode, ['bahan', 'modul', 'presentasi']))
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 9. Konfigurasi Jumlah Soal
                        TextInput::make('jml_pg')
                            ->label('Jumlah PG')
                            ->numeric()
                            ->default(10)
                            ->live(onBlur: true)
                            ->visible(fn (Get $get) => $this->mode === 'soal' && in_array($get('assessment_type'), ['sumatif_lingkup', 'sumatif_sas']))
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        TextInput::make('jml_isian')
                            ->label('Jumlah Isian')
                            ->numeric()
                            ->default(5)
                            ->live(onBlur: true)
                            ->visible(fn (Get $get) => $this->mode === 'soal' && in_array($get('assessment_type'), ['sumatif_lingkup', 'sumatif_sas']))
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        TextInput::make('jml_essay')
                            ->label('Jumlah Uraian')
                            ->numeric()
                            ->default(5)
                            ->live(onBlur: true)
                            ->visible(fn (Get $get) => $this->mode === 'soal' && in_array($get('assessment_type'), ['sumatif_lingkup', 'sumatif_sas']))
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 10. Input P5
                        Select::make('p5_theme')
                            ->label('Tema Projek P5')
                            ->options([
                                'Gaya Hidup Berkelanjutan' => 'Gaya Hidup Berkelanjutan',
                                'Kearifan Lokal' => 'Kearifan Lokal',
                                'Bhinneka Tunggal Ika' => 'Bhinneka Tunggal Ika',
                                'Bangunlah Jiwa dan Raganya' => 'Bangunlah Jiwa dan Raganya',
                                'Rekayasa dan Teknologi' => 'Rekayasa dan Teknologi',
                                'Kewirausahaan' => 'Kewirausahaan',
                            ])
                            ->visible(fn (Get $get) => $this->mode === 'soal' && $get('assessment_type') === 'proyek')
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        TextInput::make('p5_dimension')
                            ->label('Dimensi Profil')
                            ->placeholder('Contoh: Bergotong Royong & Kreatif')
                            ->visible(fn (Get $get) => $this->mode === 'soal' && $get('assessment_type') === 'proyek')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        // 11. Deskripsi TP / Materi
                        Textarea::make('learning_objective')
                            ->label('Deskripsi TP / CP / Daftar Materi')
                            ->rows(3)
                            ->placeholder('Otomatis terisi jika Bab dipilih, atau ketikkan secara manual...')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Textarea::make('material_detail')
                            ->label('Detail Sub-Materi / Kata Kunci')
                            ->rows(2)
                            ->placeholder('Contoh: Rantai makanan, produsen, konsumen, pengurai')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
        $this->generatePrompt();
    }

    public function generatePrompt(): void
    {
        $state = $this->form->getState();

        $phase = isset($state['phase']) ? "Fase {$state['phase']}" : 'Fase C';
        $level = isset($state['level']) ? "Kelas {$state['level']} SD" : 'Kelas 5 SD';

        $subject = isset($state['subject_id'])
            ? (Subject::find($state['subject_id'])?->name ?? '[Mata Pelajaran]')
            : '[Mata Pelajaran]';

        $topic = isset($state['sumative_scope_id'])
            ? (SumativeScope::find($state['sumative_scope_id'])?->name ?? '[Lingkup Materi / Bab]')
            : '[Lingkup Materi / Bab]';

        $materi = $state['material_detail'] ?? '[Sub-Materi / Kata Kunci]';
        $tp = $state['learning_objective'] ?? '[Tujuan Pembelajaran / CP]';
        $model = $state['model'] ?? 'Inkuiri-Discovery Learning';
        $maxSlides = $state['max_slides'] ?? 10;

        if ($this->mode === 'presentasi') {
            $this->generatedPrompt = "Bertindaklah sebagai Perancang Media Pembelajaran SD & Ahli Desain Presentasi Interaktif.\n\n"
                . "Tugas: Buatkan **Draf Slide Presentasi Pembelajaran (Maksimal {$maxSlides} Slide)** yang ringkas, interaktif, dan mudah dipahami anak SD.\n\n"
                . "Informasi Pembelajaran:\n"
                . "- Jenjang & Kelas: {$level} ({$phase})\n"
                . "- Mata Pelajaran: {$subject}\n"
                . "- Topik / Bab: {$topic}\n"
                . "- Detail Sub-Materi: {$materi}\n"
                . "- Tujuan Pembelajaran (TP):\n{$tp}\n"
                . "- Pendekatan Pembelajaran: {$model}\n";
        } elseif ($this->mode === 'soal') {
            $this->generatedPrompt = "Bertindaklah sebagai Pembuat Soal SD Kurikulum Merdeka.\n"
                . "Buatkan **Naskah Soal** untuk **{$level} ({$phase})**.\n"
                . "Mata Pelajaran: {$subject}, Bab: {$topic}, TP:\n{$tp}\n";
        } else {
            $this->generatedPrompt = "Buatkan bahan ajar atau modul mendalam untuk {$subject} kelas {$level} dengan topik {$topic}.";
        }
    }
}