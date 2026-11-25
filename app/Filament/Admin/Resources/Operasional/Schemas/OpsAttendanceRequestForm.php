<?php

namespace App\Filament\Admin\Resources\Operasional\Schemas;

use App\Models\OpsAttendanceRequest;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\FileUpload;

class OpsAttendanceRequestForm
{
    public static function getSchema(): Schema
    {
        return Schema::make([
            Section::make('Detail Request')
                ->schema([
                    Select::make('class_id')
                        ->label('Kelas')
                        ->relationship('class', 'name')
                        ->required()
                        ->searchable(),

                    Select::make('student_id')
                        ->label('Siswa')
                        ->relationship('student', 'name')
                        ->required()
                        ->searchable(),

                    Select::make('teacher_id')
                        ->label('Wali Kelas')
                        ->relationship('teacher', 'name')
                        ->nullable()
                        ->searchable(),

                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->required(),

                    Textarea::make('notes')
                        ->label('Alasan')
                        ->nullable(),

                    FileUpload::make('attachment_path')
                        ->label('Lampiran')
                        ->nullable()
                        ->disk('public'),

                    Select::make('approval_status')
                        ->label('Status Persetujuan')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ])
                        ->required(),
                ]),
        ]);
    }
}
