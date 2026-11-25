<?php

namespace App\Filament\Admin\Resources\GradeExtracurriculars\Pages;

use App\Filament\Admin\Resources\GradeExtracurriculars\GradeExtracurricularResource;
use App\Models\Enrollment;
use App\Models\GradeExtracurricular;
use App\Models\ExtracurricularScore;
use App\Models\SchoolClass;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class BulkExtracurricularScore extends Page
{
    protected static string $resource = GradeExtracurricularResource::class;

    // Menyesuaikan jalur view Blade agar sesuai dengan folder resource
    protected string $view = 'filament.admin.bulk-extracurricular-score';

    protected static ?string $title = 'Input Nilai Ekstrakurikuler Massal';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'class_id'                 => null,
            'grade_extracurricular_id' => null,
            'students'                 => [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Parameter Ekstrakurikuler')
                    ->description('Pilih Kelas dan Kegiatan Ekstrakurikuler untuk memuat daftar siswa.')
                    ->schema([
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('students', $this->loadStudentsData($state));
                            })
                            ->required()
                            ->dehydrated(false),

                        Select::make('grade_extracurricular_id')
                            ->label('Kegiatan Ekstrakurikuler')
                            ->options(GradeExtracurricular::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                Section::make('Daftar Nilai & Deskripsi Siswa')
                    ->schema([
                        Repeater::make('students')
                            ->label('')
                            ->itemLabel(fn (array $state): ?string => $this->getStudentLabel($state))
                            ->grid([
                                'default' => 1,
                                'sm'      => 1,
                                'md'      => 2,
                                'xl'      => 2,
                            ])
                            ->schema([
                                Hidden::make('enrollment_id'),

                                Select::make('grade')
                                    ->label('Predikat (Grade)')
                                    ->options([
                                        'A' => 'A (Sangat Baik)',
                                        'B' => 'B (Baik)',
                                        'C' => 'C (Cukup)',
                                        'D' => 'D (Kurang)',
                                    ])
                                    ->nullable()
                                    ->placeholder('Pilih Grade'),

                                Textarea::make('description')
                                    ->label('Deskripsi Kegiatan / Catatan')
                                    ->rows(2)
                                    ->nullable()
                                    ->placeholder('Tulis deskripsi atau catatan...'),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ])
            ->statePath('data');
    }

    protected function loadStudentsData(int|string|null $classId): array
    {
        if (blank($classId)) {
            return [];
        }

        return Enrollment::where('class_id', $classId)
            ->with('student')
            ->get()
            ->sortBy(fn ($enrollment) => $enrollment->student?->name ?? '')
            ->map(function ($enrollment) {
                return [
                    'enrollment_id' => $enrollment->id,
                    'student_name'  => $enrollment->student?->name ?? 'Siswa',
                    'nis'           => $enrollment->student?->nis ?? null,
                    'grade'         => null,
                    'description'   => null,
                ];
            })
            ->values()
            ->toArray();
    }

    protected function getStudentLabel(array $state): ?string
    {
        $name = $state['student_name'] ?? 'Siswa';
        if (! empty($state['nis'])) {
            return "{$name} ({$state['nis']})";
        }

        return $name;
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $extracurricularId = $formData['grade_extracurricular_id'] ?? null;
        $students          = $formData['students'] ?? [];

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

            foreach ($students as $student) {
                if (!empty($student['grade']) || !empty($student['description'])) {
                    ExtracurricularScore::updateOrCreate(
                        [
                            'enrollment_id'      => $student['enrollment_id'],
                            'extracurricular_id' => $extracurricularId,
                        ],
                        [
                            'grade'       => $student['grade'],
                            'description' => $student['description'],
                        ]
                    );

                    $countSaved++;
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