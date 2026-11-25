<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\SumatifResource;
use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Sumatif;
use App\Models\SumativeScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;

class BulkSumatifByScope extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SumatifResource::class;

    protected string $view = 'filament.admin.bulk-sumatif-by-scope';

    protected static ?string $title = 'Input Sumatif Massal';

    public ?array $data = [];

    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->form->fill([
            'class_id'          => null,
            'subject_id'        => null,
            'sumative_scope_id' => null,
            'type'              => 'SLM',
            'students'          => [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter Asesmen Sumatif')
                    ->description('Pilih Kelas, Mata Pelajaran, Scope, dan Tipe Sumatif untuk memuat lembar kerja nilai siswa.')
                    ->schema([
                        // 1. Pilih Kelas (Filter Siswa)
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),

                        // 2. Pilih Mata Pelajaran
                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {$set('sumative_scope_id', null);
                                $this->loadStudentsData($set);
                            })
                            ->required(),

                        // 3. Pilih Scope / Lingkup Materi (Opsional, misal kosong untuk STS/SAS)
                        Select::make('sumative_scope_id')
                            ->label('Lingkup Materi (Scope)')
                            ->placeholder('Semua / Tanpa Scope (STS/SAS)')
                            ->searchable()
                            ->nullable()
                            ->live()
                            ->options(function (Get $get) {
                                $subjectId =$get('subject_id');

                                if (! $subjectId) {
                                    return [];
                                }

                                return SumativeScope::where('subject_id', $subjectId)
                                    ->get()
                                    ->mapWithKeys(function ($scope) {
                                        return [(string) $scope->id =>$scope->name];
                                    })
                                    ->toArray();
                            })
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->disabled(fn (Get $get) => !$get('subject_id')),

                        // 4. Pilih Tipe Sumatif
                        Select::make('type')
                            ->label('Tipe Sumatif')
                            ->options([
                                'SLM' => 'Sumatif Lingkup Materi (SLM)',
                                'STS' => 'Sumatif Tengah Semester (STS)',
                                'SAS' => 'Sumatif Akhir Semester (SAS)',
                            ])
                            ->default('SLM')
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),
                    ])->columns(4),
            ])
            ->statePath('data');
    }

    public function loadStudentsData(Set $set): void
    {
        $classId   =$this->data['class_id'] ?? null;
        $subjectId =$this->data['subject_id'] ?? null;
        $scopeId   = filled($this->data['sumative_scope_id'] ?? null) ? $this->data['sumative_scope_id'] : null;
        $type      =$this->data['type'] ?? 'SLM';

        if (blank($classId) || blank($subjectId)) {$set('students', []);
            return;
        }

        $existingScores = Sumatif::where('subject_id',$subjectId)
            ->where('type', $type)
            ->when(
                $scopeId,
                fn ($query) => $query->where('sumative_scope_id',$scopeId),
                fn ($query) =>$query->whereNull('sumative_scope_id')
            )
            ->get()
            ->keyBy('enrollment_id');

        $students = Enrollment::where('class_id',$classId)
            ->with('student')
            ->get()
            ->sortBy(fn ($enrollment) =>$enrollment->student?->name ?? '')
            ->map(function ($enrollment) use ($existingScores) {$existing = $existingScores->get($enrollment->id);

                return [
                    'enrollment_id' => $enrollment->id,
                    'student_name'  => $enrollment->student?->name ?? 'Siswa',
                    'nis'           => $enrollment->student?->nis ?? '-',
                    'score'         => $existing?->score,
                ];
            })
            ->values()
            ->toArray();

        $set('students',$students);
    }

    public function save(): void
    {
        $subjectId =$this->data['subject_id'] ?? null;
        $scopeId   = filled($this->data['sumative_scope_id'] ?? null) ? $this->data['sumative_scope_id'] : null;
        $type      =$this->data['type'] ?? 'SLM';
        $students  =$this->data['students'] ?? [];

        if (! $subjectId) {
            Notification::make()
                ->title('Gagal')
                ->body('Pilih Mata Pelajaran terlebih dahulu.')
                ->danger()
                ->send();

            return;
        }

        $countSaved = 0;

        try {
            DB::beginTransaction();

            foreach ($students as$student) {
                $enrollmentId =$student['enrollment_id'];
                $rawScore     =$student['score'] ?? null;

                if (! is_null($rawScore) && $rawScore !== '') {
                    $numericScore = (float) str_replace(',', '.', (string) $rawScore);

                    if ($numericScore < 0 || $numericScore > 100) {
                        throw new \Exception("Nilai untuk {$student['student_name']} harus berada di antara 0 - 100.");
                    }

                    Sumatif::updateOrCreate(
                        [
                            'enrollment_id'     => $enrollmentId,
                            'subject_id'        => $subjectId,
                            'sumative_scope_id' => $scopeId,
                            'type'              => $type,
                        ],
                        [
                            'score' => $numericScore,
                        ]
                    );

                    $countSaved++;
                } else {
                    Sumatif::where('enrollment_id', $enrollmentId)
                        ->where('subject_id', $subjectId)
                        ->where('type', $type)
                        ->when(
                            $scopeId,
                            fn ($query) => $query->where('sumative_scope_id', $scopeId),
                            fn ($query) => $query->whereNull('sumative_scope_id')
                        )
                        ->delete();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error')
                ->body('Gagal menyimpan: ' . $e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Berhasil Disimpan')
            ->body("Sebanyak {$countSaved} nilai sumatif siswa berhasil dicatat.")
            ->success()
            ->send();
    }
}