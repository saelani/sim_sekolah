<?php

namespace App\Filament\Admin\Resources\Students\Schemas;

use App\Models\SchoolProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Forms;

class StudentForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->nullable()
                    ->label('User'),

                Forms\Components\TextInput::make('nis')
                    ->required()
                    ->maxLength(20)
                    ->label('NIS'),

                Forms\Components\TextInput::make('nisn')
                    ->required()
                    ->maxLength(10)
                    ->label('NISN'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),

                Forms\Components\Select::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required()
                    ->label('Jenis Kelamin'),

                Forms\Components\Select::make('religion')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                    ])
                    ->default('Islam')
                    ->label('Agama'),

                Forms\Components\TextInput::make('pob')
                    ->maxLength(100)
                    ->label('Tempat Lahir'),

                Forms\Components\DatePicker::make('dob')
                    ->label('Tanggal Lahir'),

                Forms\Components\Textarea::make('address')
                    ->rows(3)
                    ->label('Alamat'),

                Forms\Components\TextInput::make('parent_name')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Nama Ortu'),

                Forms\Components\FileUpload::make('photo_path')
                    ->image() // Membatasi hanya file gambar yang bisa di-upload
                    ->directory('student') // Folder penyimpanan di dalam disk storage (storage/app/public/photos)
                    ->disk('public') // Menggunakan disk public agar gambar bisa diakses publik
                    ->nullable()
                    ->label('Foto'),


                Forms\Components\Select::make('class_id')
                    ->relationship('schoolClass', 'name')
                    ->nullable()
                    ->label('Kelas'),

                Section::make('Profil Sekolah (Referensi)')
                    ->schema([
                        View::make('filament.admin.resources.students.form.school-profile')
                            ->viewData(['schoolProfile' => static::getSchoolProfile()]),
                    ]),
            ]);
    }

    public static function getSchoolProfile(): ?SchoolProfile
    {
        return SchoolProfile::first();
    }
}
