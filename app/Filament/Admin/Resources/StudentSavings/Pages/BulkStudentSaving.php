<?php

namespace App\Filament\Admin\Resources\StudentSavings\Pages;

use App\Filament\Admin\Resources\StudentSavings\StudentSavingResource;
use App\Models\Student;
use App\Models\StudentSavings;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
// use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BulkStudentSaving extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentSavingResource::class;

    protected string $view = 'filament.admin.bulk-student-saving';

    protected static ?string $title = 'Input Tabungan Massal Siswa';

    public ?array $data = [];

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function mount(): void
    {
        $this->data['date'] = now()->format('Y-m-d');
        $this->data['type'] = 'in';
        $this->data['students'] = $this->loadAllStudentsData();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Parameter Transaksi')
                ->schema([
                    DatePicker::make('date')
                        ->label('Tanggal Transaksi')
                        ->required(),

                    Select::make('type')
                        ->label('Jenis Transaksi')
                        ->options([
                            'in'  => 'Setor (Masuk)',
                            'out' => 'Tarik (Keluar)',
                        ])
                        ->required(),
                ])->columns(2),

            Section::make('Daftar Seluruh Siswa')
                ->schema([
                    Repeater::make('students')
                        ->label('Daftar Siswa')
                        ->itemLabel(fn (array $state): ?string => $this->getStudentLabel($state))
                        ->grid([
                            'default' => 1,
                            'sm'      => 2,
                            'md'      => 3,
                            'xl'      => 4,
                        ])
                        ->schema([
                            Hidden::make('student_id'),

                            TextInput::make('amount')
                                ->hiddenLabel()
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('0'),
                        ])
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                ]),
        ];
    }

    protected function loadAllStudentsData(): array
    {
        $query = Student::query()->orderBy('name', 'asc');

        $user = Auth::user();
        $userRole = strtolower($user->role ?? $user->role_type ?? '');

        if (! in_array($userRole, ['admin', 'headmaster', 'super_admin']) && ! ($user->is_admin ?? false)) {
            $teacherId = $user->teacher->first()?->id ?? DB::table('acad_teachers')->where('user_id', $user->id)->value('id');

            if ($teacherId) {
                $homeroomClassIds = DB::table('acad_classes')->where('teacher_id', $teacherId)->pluck('id')->toArray();
                $subjectClassIds  = DB::table('acad_teacher_subject_classes')->where('teacher_id', $teacherId)->pluck('class_id')->toArray();
                $allowedClassIds  = array_unique(array_merge($homeroomClassIds, $subjectClassIds));

                $query->whereIn('class_id', empty($allowedClassIds) ? [0] : $allowedClassIds);
            }
        }

        return $query->get()->map(function ($student) {
            return [
                'student_id'   => $student->id,
                'student_name' => $student->name,
                'nis'          => $student->nis,
                'amount'       => null,
            ];
        })->toArray();
    }

    protected function getStudentLabel(array $state): ?string
    {
        $name = $state['student_name'] ?? 'Siswa';

        if (isset($state['nis']) && $state['nis']) {
            return "{$name} ({$state['nis']})";
        }

        return $name;
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $date     = $formData['date'] ?? now()->format('Y-m-d');
        $type     = $formData['type'] ?? 'in';
        $students = $formData['students'] ?? [];

        $countSaved = 0;

        foreach ($students as $student) {
            if (isset($student['amount']) && $student['amount'] !== '' && (float) $student['amount'] > 0) {
                StudentSavings::create([
                    'student_id'  => $student['student_id'],
                    'user_id'     => Auth::id(),
                    'date'        => $date,
                    'type'        => $type,
                    'amount'      => (float) $student['amount'],
                    'description' => null,
                ]);

                $countSaved++;
            }
        }

        if ($countSaved === 0) {
            Notification::make()
                ->title('Informasi')
                ->body('Tidak ada nominal transaksi yang diisi.')
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Berhasil Disimpan')
            ->body("Sebanyak {$countSaved} data transaksi tabungan berhasil dicatat.")
            ->success()
            ->send();

        $this->redirect(StudentSavingResource::getUrl('index'));
    }
}