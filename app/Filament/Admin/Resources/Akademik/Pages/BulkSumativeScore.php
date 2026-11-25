<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumativeScopeResource;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SumativeScope;
use App\Models\Sumatif;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;

class BulkSumativeScore extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.admin.bulk-sumative-score';

    protected static ?string $title = 'Input Nilai Sumatif Massal';

    protected static string $resource = SumativeScopeResource::class;

    public ?array $data = [];

    public array $sumativeScopes = [];

    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->form->fill([
            'class_id'   => null,
            'subject_id' => null,
            'phase'      => null,
            'semester'   => null,
            'type'       => 'lingkup_materi',
            'students'   => [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Filter Parameter Nilai Sumatif')
                    ->description('Pilih Kelas, Mata Pelajaran, Fase, dan Semester untuk memuat lembar kerja nilai.')
                    ->schema([
                        Select::make('class_id')
                            ->label('Kelas (Untuk Daftar Siswa)')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadMatrixData($set))
                            ->required(),

                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadMatrixData($set))
                            ->required(),

                        Select::make('phase')
                            ->label('Fase (Lingkup Materi)')
                            ->options([
                                'A' => 'Fase A',
                                'B' => 'Fase B',
                                'C' => 'Fase C',
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadMatrixData($set))
                            ->required(),

                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                '1' => 'Semester 1 (Ganjil)',
                                '2' => 'Semester 2 (Genap)',
                            ])
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadMatrixData($set))
                            ->required(),

                        Select::make('type')
                            ->label('Tipe Sumatif')
                            ->options([
                                'lingkup_materi'  => 'Lingkup Materi',
                                'tengah_semester' => 'Tengah Semester',
                                'akhir_semester'  => 'Akhir Semester',
                            ])
                            ->default('lingkup_materi')
                            ->required(),
                    ])->columns(5),
            ])
            ->statePath('data');
    }

    public function loadMatrixData(Set $set): void
    {
        $classId   = $this->data['class_id'] ?? null;
        $subjectId = $this->data['subject_id'] ?? null;
        $phase     = $this->data['phase'] ?? null;
        $semester  = $this->data['semester'] ?? null;

        if (blank($subjectId) || blank($phase) || blank($semester)) {
            $this->sumativeScopes = [];
            $set('students', []);
            return;
        }

        // 1. Ambil daftar Lingkup Materi berdasarkan Subject, Phase, dan Semester
        $scopes = SumativeScope::where('subject_id', $subjectId)
            ->where('phase', $phase)
            ->where('semester', $semester)
            ->get();

        $this->sumativeScopes = $scopes->pluck('name', 'id')->toArray();

        if ($scopes->isEmpty() || blank($classId)) {
            $set('students', []);
            return;
        }

        // 2. Ambil daftar siswa berdasarkan kelas yang dipilih
        $students = Enrollment::where('class_id', $classId)
            ->with('student')
            ->get()
            ->sortBy(fn ($enrollment) => $enrollment->student?->name ?? '')
            ->map(function ($enrollment) use ($scopes, $subjectId) {
                $row = [
                    'enrollment_id' => $enrollment->id,
                    'student_name'  => $enrollment->student?->name ?? 'Siswa',
                    'nis'           => $enrollment->student?->nis ?? '-',
                    'scores'        => [],
                ];

                foreach ($scopes as $scope) {
                    $existingScore = Sumatif::where('enrollment_id', $enrollment->id)
                        ->where('subject_id', $subjectId)
                        ->where('sumative_scope_id', $scope->id)
                        ->value('score');

                    $row['scores'][$scope->id] = $existingScore;
                }

                return $row;
            })
            ->values()
            ->toArray();

        $set('students', $students);
    }

    public function save(): void
    {
        $formData  = $this->form->getState();
        $students  = $this->data['students'] ?? [];
        $subjectId = $formData['subject_id'] ?? null;
        $type      = $formData['type'] ?? 'lingkup_materi';

        if (! $subjectId) {
            Notification::make()->title('Error')->body('Mata pelajaran harus dipilih.')->danger()->send();
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($students as $student) {
                $enrollmentId = $student['enrollment_id'];
                $scores       = $student['scores'] ?? [];

                foreach ($scores as $scopeId => $scoreValue) {
                    if (! is_null($scoreValue) && $scoreValue !== '') {
                        $numericScore = (float) str_replace(',', '.', (string) $scoreValue);

                        if ($numericScore < 0 || $numericScore > 100) {
                            throw new \Exception("Nilai untuk {$student['student_name']} harus berada di antara 0 - 100.");
                        }

                        Sumatif::updateOrCreate(
                            [
                                'enrollment_id'     => $enrollmentId,
                                'subject_id'        => $subjectId,
                                'sumative_scope_id' => $scopeId,
                            ],
                            [
                                'type'  => $type,
                                'score' => $numericScore,
                            ]
                        );
                    } else {
                        Sumatif::where('enrollment_id', $enrollmentId)
                            ->where('subject_id', $subjectId)
                            ->where('sumative_scope_id', $scopeId)
                            ->delete();
                    }
                }
            }

            DB::commit();

            Notification::make()
                ->title('Berhasil Disimpan')
                ->body('Seluruh nilai sumatif berhasil diperbarui ke database.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Gagal Menyimpan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}