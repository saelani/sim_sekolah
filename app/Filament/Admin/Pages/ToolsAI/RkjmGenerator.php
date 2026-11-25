<?php

namespace App\Filament\Admin\Pages\ToolsAI;

use App\Models\SchoolProfile;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class RkjmGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | UnitEnum | null $navigationGroup = 'Tools AI';
    protected static ?string $title = 'Generator Prompt RKJM';

    protected string $view = 'filament.admin.pages.tools-a-i.rkjm-generator';

    public ?array $data = [];
    public string $promptOutput = '';

    public function mount(): void
    {
        $sekolah = SchoolProfile::first();

        // Mengisi form otomatis saat halaman pertama kali dibuka
        $this->form->fill([
            'namaSekolah' => $sekolah?->school_name ?? '',
            'npsn'        => $sekolah?->npsn ?? '',
            'alamat'      => $sekolah?->address ?? '',
            'visi'        => trim(strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $sekolah?->vision ?? ''))),
            'misi'        => trim(strip_tags(str_replace(['<br>', '<br/>', '</p>', '</li>'], "\n", $sekolah?->mission ?? ''))),
            'periode'     => '2026–2030',
        ]);

        $this->generatePrompt();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Dasar Sekolah')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\TextInput::make('namaSekolah')
                            ->label('Nama Sekolah')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\TextInput::make('npsn')
                            ->label('NPSN')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Select::make('status')
                            ->label('Status Sekolah')
                            ->options([
                                'Negeri' => 'Negeri',
                                'Swasta' => 'Swasta',
                            ])
                            ->live()
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\TextInput::make('periode')
                            ->label('Periode RKJM')
                            ->default('2026–2030')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),
                    ])
                    ->columns(1),

                Section::make('Arah Kebijakan Sekolah')
                    ->icon('heroicon-o-flag')
                    ->schema([
                        Forms\Components\Textarea::make('visi')
                            ->label('Visi Sekolah')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('misi')
                            ->label('Misi Sekolah')
                            ->rows(3)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('tujuan')
                            ->label('Tujuan Jangka Menengah')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('sasaran')
                            ->label('Sasaran Strategis')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),
                    ])
                    ->columns(1),

                Section::make('Data Rapor & Perencanaan')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\Textarea::make('rapor')
                            ->label('Ringkasan Rapor Pendidikan')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('edsp')
                            ->label('Hasil EDSP')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('akademik')
                            ->label('Capaian Akademik')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),

                        Forms\Components\Textarea::make('nonakademik')
                            ->label('Capaian Nonakademik')
                            ->rows(2)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn () => $this->generatePrompt()),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function generatePrompt(): void
    {
        $d = $this->data;

        $this->promptOutput = "Bertindaklah sebagai Konsultan Perencanaan Pendidikan Nasional, Pengawas Sekolah Senior, Tim Penjaminan Mutu Pendidikan, Auditor Manajemen Sekolah, dan Ahli Perencanaan Berbasis Data.\n\n"
            . "Tugas Anda adalah menyusun dokumen \"Rencana Kerja Jangka Menengah (RKJM)\" Sekolah periode 4 tahun yang komprehensif berbasis data.\n\n"
            . "### 1. DATA SEKOLAH ###\n"
            . "- Nama Sekolah: " . ($d['namaSekolah'] ?? '[BELUM DIISI]') . "\n"
            . "- NPSN: " . ($d['npsn'] ?? '[BELUM DIISI]') . "\n"
            . "- Status: " . ($d['status'] ?? '[BELUM DIISI]') . "\n"
            . "- Alamat: " . ($d['alamat'] ?? '[BELUM DIISI]') . "\n"
            . "- Periode RKJM: " . ($d['periode'] ?? '2026–2030') . "\n\n"
            . "### 2. ARAH KEBIJAKAN ###\n"
            . "- Visi: " . ($d['visi'] ?? '[BELUM DIISI]') . "\n"
            . "- Misi: " . ($d['misi'] ?? '[BELUM DIISI]') . "\n"
            . "- Tujuan: " . ($d['tujuan'] ?? '[BELUM DIISI]') . "\n"
            . "- Sasaran Strategis: " . ($d['sasaran'] ?? '[BELUM DIISI]') . "\n\n"
            . "### 3. DATA PERENCANAAN ###\n"
            . "- Rapor Pendidikan: " . ($d['rapor'] ?? '[BELUM DIISI]') . "\n"
            . "- Hasil EDSP: " . ($d['edsp'] ?? '[BELUM DIISI]') . "\n"
            . "- Capaian Akademik: " . ($d['akademik'] ?? '[BELUM DIISI]') . "\n"
            . "- Capaian Nonakademik: " . ($d['nonakademik'] ?? '[BELUM DIISI]') . "\n\n"
            . "Tolong susun dokumen RKJM lengkap dengan sistematika resmi (A-K).";
    }
}