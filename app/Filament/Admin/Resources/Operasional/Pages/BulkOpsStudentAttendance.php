<?php

namespace App\Filament\Admin\Resources\Operasional\Pages;

use App\Filament\Admin\Resources\Operasional\OpsStudentAttendanceResource;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\OpsStudentAttendance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BulkOpsStudentAttendance extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = OpsStudentAttendanceResource::class;

    protected string $view = 'filament.admin.bulk-student-attendance';

    protected static ?string $title = 'Input Kehadiran Massal Siswa';

    public ?array $data = [];

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function mount(): void
    {
        $this->data['date'] = now()->format('Y-m-d');
        $this->data['status'] = 'Hadir';
        $this->data['students'] = $this->loadAllStudentsData();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Parameter Kehadiran Massal')
                ->description('Tentukan tanggal dan status default, lalu ubah per siswa jika diperlukan.')
                ->schema([
                    DatePicker::make('date')
                        ->label('Tanggal Kehadiran')
                        ->required(),

                    Select::make('status')
                        ->label('Status Default')
                        ->options([
                            'Hadir' => 'Hadir',
                            'Sakit' => 'Sakit',
                            'Izin'  => 'Izin',
                            'Alpa'  => 'Alpa',
                        ])
                        ->required(),
                ])->columns(2),

            Section::make('Daftar Siswa di Kelas Anda')
                ->schema([
                    Repeater::make('students')
                        ->label('')
                        ->itemLabel(fn (array $state): ?string => $this->getStudentLabel($state))
                        ->grid([
                            'default' => 1,
                            'sm'      => 2,
                            'md'      => 3,
                            'xl'      => 4,
                        ])
                        ->schema([
                            Hidden::make('student_id'),
                            Hidden::make('class_id'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Hadir' => 'Hadir',
                                    'Sakit' => 'Sakit',
                                    'Izin'  => 'Izin',
                                    'Alpa'  => 'Alpa',
                                ])
                                ->required(),

                            TextInput::make('notes')
                                ->label('Catatan')
                                ->placeholder('Opsional'),
                        ])
                        ->columns(1)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                ]),
        ];
    }

    protected function getStudentLabel(array $state): ?string
    {
        $name = $state['student_name'] ?? 'Siswa';

        if (isset($state['nis']) && $state['nis']) {
            return "{$name} ({$state['nis']})";
        }

        return $name;
    }

    protected function loadAllStudentsData(): array
    {
        $user = Auth::user();
        
        $teacher = $user ? Teacher::where('user_id', $user->id)->first() : null;
        $class = $teacher ? SchoolClass::where('teacher_id', $teacher->id)->first() : null;

        $query = Student::query()->orderBy('name', 'asc');

        $userRole = strtolower($user->role ?? $user->role_type ?? '');

        if (! in_array($userRole, ['admin', 'headmaster', 'super_admin']) && ! ($user->is_admin ?? false)) {
            if ($class) {
                $query->where('class_id', $class->id);
            } else {
                $query->where('id', 0);
            }
        }

        return $query->get()->map(function ($student) use ($class) {
            return [
                'student_id'   => $student->id,
                'class_id'     => $student->class_id ?? ($class?->id),
                'student_name' => $student->name,
                'nis'          => $student->nis,
                'status'       => 'Hadir',
                'notes'        => null,
            ];
        })->toArray();
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $date     = $formData['date'] ?? now()->format('Y-m-d');
        $students = $formData['students'] ?? [];

        $user = Auth::user();
        $teacher = $user ? Teacher::where('user_id', $user->id)->first() : null;
        $teacherId = $teacher ? $teacher->id : DB::table('acad_teachers')->where('user_id', $user->id)->value('id');

        $countSaved = 0;

        try {
            DB::beginTransaction();

            foreach ($students as $student) {
                $statusVal = $student['status'] ?? 'Hadir';
                $studentId = $student['student_id'];

                if (strtolower($statusVal) === 'hadir') {
                    OpsStudentAttendance::where('student_id', $studentId)
                        ->where('date', $date)
                        ->delete();
                    continue;
                }

                OpsStudentAttendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date'       => $date,
                    ],
                    [
                        'class_id'   => $student['class_id'] ?? $this->getStudentClassId($studentId),
                        'teacher_id' => $teacherId,
                        'user_id'    => $user->id,
                        'status'     => $statusVal,
                        'notes'      => $student['notes'] ?? null,
                    ]
                );

                $countSaved++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Terjadi Kesalahan')
                ->body('Gagal menyimpan data: ' . $e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Berhasil Disimpan')
            ->body("Sebanyak {$countSaved} data ketidakhadiran siswa berhasil diperbarui.")
            ->success()
            ->send();

        $this->redirect(OpsStudentAttendanceResource::getUrl('index'));
    }

    protected function getStudentClassId(int $studentId): ?int
    {
        $student = Student::find($studentId);
        return $student ? $student->class_id : null;
    }
}