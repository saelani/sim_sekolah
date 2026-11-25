<?php

namespace App\Filament\Admin\Resources\Exam\Schemas;

use App\Models\LearningObjective;
use App\Models\Subject;
use App\Models\SumativeScope;
use Filament\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi & Konfigurasi Ujian')
                    ->description('Atur jenis penilaian, mata pelajaran, tujuan pembelajaran/lingkup materi, jadwal, serta pengaturan ujian.')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('assessment_type')
                            ->label('Jenis Penilaian')
                            ->options([
                                'formatif' => 'Formatif (Tujuan Pembelajaran)',
                                'SLM'      => 'Sumatif Lingkup Materi / Bab (SLM)',
                                'STS'      => 'Sumatif Tengah Semester (STS)',
                                'SAS'      => 'Sumatif Akhir Semester (SAS)',
                            ])
                            ->default('formatif')
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('learning_objective_id', null);
                                $set('sumative_scope_id', null);
                            })
                            ->required(),

                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(fn () => Subject::query()->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('learning_objective_id', null);
                                $set('sumative_scope_id', null);
                            })
                            ->required(),

                        Select::make('learning_objective_id')
                            ->label('Tujuan Pembelajaran (TP)')
                            ->options(function (Get $get) {
                                $subjectId = $get('subject_id');
                                if (! $subjectId) {
                                    return [];
                                }

                                return LearningObjective::where('subject_id', $subjectId)
                                    ->get()
                                    ->mapWithKeys(fn ($tp) => [
                                        $tp->id => "[{$tp->code}] " . Str::limit($tp->description, 60),
                                    ])
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->visible(fn (Get $get) => $get('assessment_type') === 'formatif')
                            ->required(fn (Get $get) => $get('assessment_type') === 'formatif')
                            ->disabled(fn (Get $get) => ! $get('subject_id')),

                        Select::make('sumative_scope_id')
                            ->label('Lingkup Materi / Bab')
                            ->options(function (Get $get) {
                                $subjectId = $get('subject_id');
                                if (! $subjectId) {
                                    return [];
                                }

                                return SumativeScope::where('subject_id', $subjectId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->visible(fn (Get $get) => $get('assessment_type') === 'SLM')
                            ->required(fn (Get $get) => $get('assessment_type') === 'SLM')
                            ->disabled(fn (Get $get) => ! $get('subject_id')),

                        Select::make('class_room_id')
                            ->label('Kelas Target')
                            ->relationship('schoolClass', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('teacher_id')
                            ->label('Guru Pengampu')
                            ->relationship('teacher', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('Judul Ujian')
                            ->placeholder('Contoh: Tes Formatif TP 1.1 IPAS Kelas 5')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('duration_minutes')
                            ->label('Durasi (Menit)')
                            ->numeric()
                            ->minValue(1)
                            ->default(45)
                            ->required(),

                        TextInput::make('token')
                            ->label('Token Ujian')
                            ->default(fn () => strtoupper(Str::random(6)))
                            ->required()
                            ->suffixAction(
                                FormAction::make('generateToken')
                                    ->icon('heroicon-m-arrow-path')
                                    ->tooltip('Acak Token Baru')
                                    ->action(fn (Set $set) => $set('token', strtoupper(Str::random(6))))
                            ),

                        DateTimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->required()
                            ->native(false),

                        DateTimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->required()
                            ->native(false)
                            ->after('start_time'),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Jika diaktifkan, siswa dapat melihat dan mengakses tes ini.')
                            ->default(true),

                        Toggle::make('randomize_questions')
                            ->label('Acak Urutan Soal')
                            ->default(true),

                        Toggle::make('randomize_options')
                            ->label('Acak Urutan Opsi Jawaban')
                            ->default(true),

                        Toggle::make('show_result')
                            ->label('Tampilkan Hasil/Nilai')
                            ->helperText('Siswa dapat melihat nilai secara instan setelah tes selesai.')
                            ->default(true),
                    ]),

                Section::make('Bank Soal Ujian')
                    ->description('Masukkan daftar soal beserta kunci jawabannya di bawah ini.')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('questions')
                            ->relationship('questions')
                            ->label('Daftar Soal')
                            ->addActionLabel('Tambah Soal Baru')
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => isset($state['question_text']) ? strip_tags($state['question_text']) : 'Soal Baru')
                            ->schema([
                                Select::make('type')
                                    ->label('Tipe Soal')
                                    ->options([
                                        'multiple_choice' => 'Pilihan Ganda',
                                        'essay'           => 'Uraian / Essay',
                                    ])
                                    ->default('multiple_choice')
                                    ->live()
                                    ->required(),

                                TextInput::make('score_weight')
                                    ->label('Bobot Nilai')
                                    ->numeric()
                                    ->default(1.0)
                                    ->required(),

                                FileUpload::make('media_path')
                                    ->label('Gambar/Media Soal (Opsional)')
                                    ->image()
                                    ->directory('cbt-questions')
                                    ->visibility('public'),

                                RichEditor::make('question_text')
                                    ->label('Teks Soal')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'codeBlock',
                                    ])
                                    ->required(),

                                Repeater::make('options')
                                    ->relationship('options')
                                    ->label('Opsi Jawaban')
                                    ->addActionLabel('Tambah Opsi Jawaban')
                                    ->visible(fn (Get $get) => $get('type') === 'multiple_choice')
                                    ->schema([
                                        TextInput::make('option_text')
                                            ->label('Teks Jawaban')
                                            ->requiredWithout('media_path'),

                                        FileUpload::make('media_path')
                                            ->label('Gambar Opsi')
                                            ->image()
                                            ->directory('cbt-options'),

                                        Toggle::make('is_correct')
                                            ->label('Kunci Jawaban?')
                                            ->inline(false)
                                            ->default(false),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(4),
                            ]),
                    ]),
            ]);
    }

    private static function getTeacherClassIds(?object $user): array
    {
        if (! $user || ! $user->teacher) {
            return [];
        }

        $teacherId = $user->teacher->id;

        $homeroomClassIds = DB::table('acad_classes')
            ->where('teacher_id', $teacherId)
            ->pluck('id')
            ->toArray();

        $subjectClassIds = DB::table('acad_teacher_subject_classes')
            ->where('teacher_id', $teacherId)
            ->pluck('class_id')
            ->toArray();

        return array_unique(array_merge($homeroomClassIds, $subjectClassIds));
    }
}