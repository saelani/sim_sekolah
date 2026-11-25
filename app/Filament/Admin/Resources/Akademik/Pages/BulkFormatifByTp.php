<?php

namespace App\Filament\Admin\Resources\Akademik\Pages;

use App\Filament\Admin\Resources\Akademik\FormatifResource;
use App\Models\Enrollment;
use App\Models\Formatif;
use App\Models\LearningObjective;
use App\Models\SchoolClass;
use App\Models\Subject;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

class BulkFormatifByTp extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = FormatifResource::class;

    protected string $view = 'filament.admin.bulk-formatif-by-tp';

    protected static ?string $title = 'Input Formatif Massal per TP';

    public ?array $data = [];

    public function getMaxWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(): void
    {
        $this->form->fill([
            'class_id'              => null,
            'subject_id'            => null,
            'learning_objective_id' => null,
            'type'                  => 'Formatif 1',
            'students'              => [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parameter Asesmen Formatif')
                    ->description('Pilih Kelas, Mata Pelajaran, Tujuan Pembelajaran (TP), dan Tipe Formatif untuk memuat lembar kerja nilai.')
                    ->schema([
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(SchoolClass::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),

                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {$set('learning_objective_id', null);
                            })
                            ->required(),

                        Select::make('learning_objective_id')
                            ->label('Tujuan Pembelajaran (TP)')
                            ->searchable()
                            ->live()
                            ->required()
                            ->options(function (Get $get) {
                                $subjectId =$get('subject_id');

                                if (! $subjectId) {
                                    return [];
                                }

                                return LearningObjective::where('subject_id', $subjectId)
                                    ->get()
                                    ->mapWithKeys(function ($lo) {
                                        return [$lo->id => "{$lo->code} - {$lo->description}"];
                                    });
                            })
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->disabled(fn (Get $get) => !$get('subject_id')),

                        TextInput::make('type')
                            ->label('Tipe Formatif')
                            ->placeholder('Misal: Formatif 1 / Tugas / Kuis')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set) => $this->loadStudentsData($set))
                            ->required(),
                    ])->columns(4),
            ])
            ->statePath('data');
    }

    public function loadStudentsData(Set $set): void
    {
        $classId =$this->data['class_id'] ?? null;
        $loId    =$this->data['learning_objective_id'] ?? null;
        $type    = trim((string) ($this->data['type'] ?? 'Formatif 1'));

        if (blank($classId)) {$set('students', []);
            return;
        }

        $existingRecords = collect();
        if (! blank($loId) &&$type !== '') {
            $existingRecords = Formatif::where('learning_objective_id',$loId)
                ->where('type', $type)
                ->get()
                ->keyBy('enrollment_id');
        }

        $students = Enrollment::where('class_id',$classId)
            ->with('student')
            ->get()
            ->sortBy(fn ($enrollment) =>$enrollment->student?->name ?? '')
            ->map(function ($enrollment) use ($existingRecords) {$existing = $existingRecords->get($enrollment->id);

                return [
                    'enrollment_id' => $enrollment->id,
                    'student_name'  => $enrollment->student?->name ?? 'Siswa',
                    'nis'           => $enrollment->student?->nis ?? '-',
                    'score'         => $existing?->score,
                    'is_achieved'   => $existing ? (string) ((int) $existing->is_achieved) : '1',
                ];
            })
            ->values()
            ->toArray();

        $set('students',$students);
    }

    public function save(): void
    {
        $loId     =$this->data['learning_objective_id'] ?? null;
        $type     = trim((string) ($this->data['type'] ?? 'Formatif 1'));
        $students =$this->data['students'] ?? [];

        if (! $loId) {
            Notification::make()
                ->title('Gagal')
                ->body('Pilih Tujuan Pembelajaran terlebih dahulu.')
                ->danger()
                ->send();

            return;
        }

        if ($type === '') {
            Notification::make()
                ->title('Gagal')
                ->body('Tipe Formatif tidak boleh kosong.')
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

                    Formatif::updateOrCreate(
                        [
                            'enrollment_id'         => $enrollmentId,
                            'learning_objective_id' => $loId,
                            'type'                  => $type,
                        ],
                        [
                            'score'       => $numericScore,
                            'is_achieved' => (string) ($student['is_achieved'] ?? '1'),
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
            ->body("Sebanyak {$countSaved} nilai formatif siswa berhasil dicatat.")
            ->success()
            ->send();
    }
}