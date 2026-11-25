<?php

namespace App\Filament\Admin\Resources\Operasional\Schemas;

use App\Models\OpsStudentAttendance;
use App\Models\Teacher;
use App\Models\SchoolClass; // Gunakan model SchoolClass
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

class OpsStudentAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();
        
        // 1. Ambil data guru berdasarkan user yang sedang login
        $teacher = $user ? Teacher::where('user_id', $user->id)->first() : null;
        $defaultTeacherId = $teacher ? $teacher->id : null;

        // 2. Ambil class_id langsung dari tabel acad_classes di mana teacher_id adalah guru yang login
        $class = $teacher ? SchoolClass::where('teacher_id', $teacher->id)->first() : null;
        $defaultClassId = $class ? $class->id : null;

        return $schema
            ->components([
                Section::make('Detail Absen')
                    ->description('Silakan isi data kehadiran siswa di bawah ini.')
                    ->maxWidth('full')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                    ])
                    ->schema([
                        // Hidden field otomatis terisi
                        Hidden::make('class_id')
                            ->default($defaultClassId),

                        Hidden::make('teacher_id')
                            ->default($defaultTeacherId),

                        Hidden::make('user_id')
                            ->default($user ? $user->id : null),

                        // Field yang tampil ke user (hanya menampilkan siswa di kelas wali kelas tersebut)
                        // Select::make('student_id')
                        //     ->label('Siswa')
                        //     ->relationship('student', 'name', function ($query) use ($defaultClassId) {
                        //         if ($defaultClassId) {
                        //             // Memfilter siswa berdasarkan tabel enrollments atau class_id siswa
                        //             $query->where('class_id', $defaultClassId);
                        //         }
                        //     })
                        //     ->required()
                        //     ->searchable()
                        //     ->columnSpanFull(),
                        Forms\Components\Select::make('student_id')
                            ->relationship('student', 'name')
                            ->nullable()
                            ->label('Nama Siswa'),

                        Select::make('status')
                            ->label('Status Kehadiran')
                            ->options([
                                'Hadir' => 'Hadir',
                                'Sakit' => 'Sakit',
                                'Izin' => 'Izin',
                                'Alpa' => 'Alpa',
                            ])
                            ->required()
                            ->default('Hadir'),

                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->default(now())
                            ->required(),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getSchema(): Schema
    {
        return static::configure(Schema::make());
    }
}