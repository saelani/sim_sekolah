<?php

namespace App\Filament\Admin\Resources\Teachers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class TeacherForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->nullable()
                    ->label('User'),

                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->maxLength(18)
                    ->label('NIP'),

                Forms\Components\TextInput::make('nuptk')
                    // ->required()
                    ->maxLength(16)
                    ->label('NUPTK'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),

                Forms\Components\TextInput::make('front_title')
                    ->maxLength(50)
                    ->nullable()
                    ->label('Gelar Depan'),

                Forms\Components\TextInput::make('back_title')
                    ->maxLength(50)
                    ->nullable()
                    ->label('Gelar Belakang'),

                Forms\Components\Select::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required()
                    ->label('Jenis Kelamin'),

                Forms\Components\FileUpload::make('photo_path')
                    ->image() // Membatasi hanya file gambar yang bisa di-upload
                    ->directory('teacher') // Folder penyimpanan di dalam disk storage (storage/app/public/photos)
                    ->disk('public') // Menggunakan disk public agar gambar bisa diakses publik
                    ->nullable()
                    ->label('Foto'),

                Forms\Components\Select::make('role_type')
                    ->options([
                        'headmaster' => 'Kepala Sekolah',
                        'class_teacher' => 'Guru Kelas',
                        'subject_teacher' => 'Guru Mata Pelajaran',
                    ])
                    ->required()
                    ->label('Jabatan'),

                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label('Aktif'),
            ]);
    }
}
