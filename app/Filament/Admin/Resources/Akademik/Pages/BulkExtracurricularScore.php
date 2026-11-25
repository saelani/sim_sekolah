<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\ExtracurricularScoreResource;
use App\Models\Enrollment;
use App\Models\ExtracurricularScore;
use App\Models\GradeExtracurricular;
use App\Models\SchoolClass;
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

class BulkExtracurricularScore extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ExtracurricularScoreResource::class;

    protected string $view = 'filament.admin.bulk-extracurricular-score';

    protected static ?string $title = 'Input Nilai Ekstrakurikuler Massal';

    public ?array $data = [];

    public string $extracurricularName = '';

    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->form->fill([
            'class_id'                 => null,
            'grade_extracurricular_id' => null,
            'students'                 => [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Parameter Ekstrakurikuler')
                    ->description('Pilih Kelas dan Kegiatan Ekstrakurikuler untuk memuat lembar kerja nilai siswa.')
                    ->schema([
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),

                        Select::make('grade_extracurricular_id')
                            ->label('Kegiatan Ekstrakurikuler')
                            ->options(GradeExtracurricular::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function loadStudentsData(Set $set): void
    {
        $classId           =$this->data['class_id'] ?? null;
        $extracurricularId =$this->data['grade_extracurricular_id'] ?? null;

        $this->extracurricularName =$extracurricularId
            ? (GradeExtracurricular::find($extracurricularId)?->name ?? '')
            : '';

        if (blank($classId) || blank($extracurricularId)) {$set('students', []);
            return;
        }

        $existingScores = ExtracurricularScore::where('extracurricular_id',$extracurricularId)
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
                    'grade'         => $existing?->grade,
                    'description'   => $existing?->description,
                ];
            })
            ->values()
            ->toArray();

        $set('students',$students);
    }

    public function save(): void
    {
        $extracurricularId =$this->data['grade_extracurricular_id'] ?? null;
        $students          =$this->data['students'] ?? [];

        if (! $extracurricularId) {
            Notification::make()
                ->title('Gagal')
                ->body('Pilih Kegiatan Ekstrakurikuler terlebih dahulu.')
                ->danger()
                ->send();

            return;
        }

        $countSaved = 0;

        try {
            DB::beginTransaction();

            foreach ($students as$student) {
                $enrollmentId =$student['enrollment_id'];
                $grade        = isset($student['grade']) && $student['grade'] !== '' ? strtoupper(trim((string) $student['grade'])) : null;
                $description  = isset($student['description']) && trim((string) $student['description']) !== '' ? trim((string) $student['description']) : null;

                if (! is_null($grade) && ! in_array($grade, ['A', 'B', 'C', 'D'], true)) {
                    throw new \Exception("Predikat untuk {$student['student_name']} harus berupa A, B, C, atau D.");
                }

                if (! is_null($grade) || ! is_null($description)) {
                    ExtracurricularScore::updateOrCreate(
                        [
                            'enrollment_id'      => $enrollmentId,
                            'extracurricular_id' => $extracurricularId,
                        ],
                        [
                            'grade'       => $grade,
                            'description' => $description,
                        ]
                    );

                    $countSaved++;
                } else {
                    ExtracurricularScore::where('enrollment_id', $enrollmentId)
                        ->where('extracurricular_id', $extracurricularId)
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
            ->body("Sebanyak {$countSaved} data nilai ekstrakurikuler siswa berhasil diperbarui.")
            ->success()
            ->send();
    }
}