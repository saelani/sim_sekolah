<?php

namespace App\Filament\Admin\Resources\SchoolProfiles\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section; // <-- Gunakan Section dari Filament Schemas


class SchoolProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Utama Sekolah')
                    ->description('Data pokok legalitas dan akreditasi sekolah.')
                    ->schema([
                        Forms\Components\TextInput::make('npsn')
                            ->label('NPSN')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: 10203040'),

                        Forms\Components\TextInput::make('school_name')
                            ->label('Nama Sekolah')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: SD Negeri 1 Cerdas'),

                        Forms\Components\TextInput::make('headmaster_name')
                            ->label('Nama Kepala Sekolah')
                            ->maxLength(255)
                            ->placeholder('Lengkap dengan gelar'),

                        Forms\Components\Select::make('accreditation')
                            ->label('Akreditasi')
                            ->options([
                                'A' => 'Akreditasi A',
                                'B' => 'Akreditasi B',
                                'C' => 'Akreditasi C',
                            ])
                            ->default('A')
                            ->required()
                            ->native(false),
                    ])->columns(['default' => 1, 'md' => 2]),

                Section::make('Kontak & Alamat Sekolah')
                    ->description('Informasi komunikasi dan lokasi fisik sekolah.')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(30)
                            ->placeholder('Contoh: 021-1234567'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Resmi')
                            ->email()
                            ->maxLength(100)
                            ->placeholder('sdn1cerdas@kemdikbud.go.id'),

                        Forms\Components\TextInput::make('website')
                            ->label('Situs Web')
                            ->url()
                            ->maxLength(100)
                            ->placeholder('https://sdn1cerdas.sch.id'),

                        Forms\Components\Textarea::make('maps_embed')
                            ->label('Embed Google Maps (Iframe/URL)')
                            ->rows(2)
                            ->nullable() // <-- Tambahkan baris ini
                            ->placeholder('Tempelkan kode iframe dari Google Maps di sini'),

                    ])->columns(['default' => 1, 'md' => 3]),

                Section::make('Visi, Misi & Logo')
                    ->description('Visi-misi sekolah dan identitas visual.')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo Sekolah')
                            ->image()
                            ->disk('public')
                            ->directory('school-logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('vision')
                            ->label('Visi Sekolah')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                            ->columnSpan(['default' => 1, 'md' => 1]),

                        Forms\Components\RichEditor::make('mission')
                            ->label('Misi Sekolah')
                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                            ->columnSpan(['default' => 1, 'md' => 1]),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }
}