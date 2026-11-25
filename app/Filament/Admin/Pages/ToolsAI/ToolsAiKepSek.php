<?php

namespace App\Filament\Admin\Pages\ToolsAI;

use App\Models\SchoolProfile;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class ToolsAiKepSek extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string | UnitEnum | null $navigationGroup = 'Tools AI';

    protected static ?string $title = 'RKT Mega-Prompt & Draft Generator Berbasis Data';

    protected string $view = 'filament.admin.pages.tools-a-i.kepsek-generator';

    public ?array $data = [];

    public bool $showPromptPreview = false;

    public function mount(): void
    {
        $profile = SchoolProfile::first();

        $this->form->fill([
            'school_name' => $profile?->school_name ?? 'SMP Negeri 1 Cerdas Mulia',
            'npsn' => $profile?->npsn ?? '10293847',
            'jenjang' => 'SMP',
            'status' => 'Negeri',
            'tp' => '2026/2027',
            'siswa' => 480,
            'guru' => 32,
            'tendik' => 8,
            'masalah_rapor' => "1. Kemampuan numerasi peserta didik masih di bawah standar kompetensi minimum (Skor 58.40 - Kuning).\n2. Iklim keamanan sekolah terkait dengan pemahaman pencegahan kekerasan seksual dan perundungan masih perlu ditingkatkan (Skor 62.50).\n3. Penerapan metode pembelajaran interaktif (Deep Learning) secara merata di kelas belum optimal.",
            'prioritas_1' => 'Peningkatan Mutu Numerasi melalui Model Inkuiri Terbimbing di Seluruh Mapel',
            'prioritas_2' => 'Aktivasi Kampanye Stop Perundungan & Penguatan Peran TPPK Sekolah',
            'prioritas_3' => 'Implementasi Mindful, Meaningful, & Joyful Learning (Deep Learning)',
            'headmaster_name' => $profile?->headmaster_name ?? 'Dr. H. Ahmad Sunaryo, M.Pd.',
            'nip_kepsek' => '197805122005011002',
            'komite' => 'Ir. Budi Rahardjo',
            'pengawas' => 'Dra. Hj. Endang Retnowati, M.Pd.',
            'nip_pengawas' => '196911041994032001',
            'logo_path' => $profile?->logo_path ?? null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Parameter Input Sekolah')
                    ->description('Lengkapi data operasional dan acuan perencanaan sekolah')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->columns(1)
                    ->schema([
                        TextInput::make('school_name')
                            ->label('Nama Sekolah')
                            ->required(),
                        TextInput::make('npsn')
                            ->label('NPSN')
                            ->required(),
                        Select::make('jenjang')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                                'SMK' => 'SMK',
                            ])
                            ->required(),
                        Select::make('status')
                            ->options([
                                'Negeri' => 'Negeri',
                                'Swasta' => 'Swasta',
                            ])
                            ->required(),
                        TextInput::make('tp')
                            ->label('Tahun Pelajaran')
                            ->required(),

                        TextInput::make('siswa')->numeric()->label('Siswa')->required(),
                        TextInput::make('guru')->numeric()->label('Guru')->required(),
                        TextInput::make('tendik')->numeric()->label('Tendik')->required(),

                        FileUpload::make('file_rkjm')
                            ->label('Dokumen RKJM')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->directory('rkt-docs'),
                        FileUpload::make('file_rapor')
                            ->label('Rapor Pendidikan')
                            ->acceptedFileTypes(['application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                            ->directory('rkt-docs'),
                        FileUpload::make('file_logo')
                            ->label('Lambang Sekolah')
                            ->image()
                            ->directory('rkt-logos')
                            ->reactive()
                            ->afterStateUpdated(fn ($state) => $this->data['logo_path'] = $state),

                        Textarea::make('masalah_rapor')
                            ->label('Hasil Rapor Pendidikan & EDSP (Permasalahan Utama)')
                            ->rows(4)
                            ->required(),

                        Section::make('Program Prioritas Tahun Berjalan')
                            ->compact()
                            ->columns(1)
                            ->schema([
                                TextInput::make('prioritas_1')->label('Prioritas 1')->required(),
                                TextInput::make('prioritas_2')->label('Prioritas 2')->required(),
                                TextInput::make('prioritas_3')->label('Prioritas 3')->required(),
                            ]),

                        Section::make('Pejabat Pengesah / Tanda Tangan')
                            ->compact()
                            ->columns(1)
                            ->schema([
                                TextInput::make('headmaster_name')->label('Kepala Sekolah')->required(),
                                TextInput::make('nip_kepsek')->label('NIP Kepala Sekolah')->required(),
                                TextInput::make('komite')->label('Ketua Komite Sekolah')->required(),
                                TextInput::make('pengawas')->label('Pengawas Sekolah')->required(),
                                TextInput::make('nip_pengawas')->label('NIP Pengawas')->required(),
                            ]),
                    ])
                    ->footerActions([
                        Action::make('syncData')
                            ->label('Sinkronkan & Update Draf RKT')
                            ->icon('heroicon-m-arrow-path')
                            ->color('primary')
                            ->action(function () {
                                Notification::make()
                                    ->title('Data RKT Berhasil Disinkronkan')
                                    ->success()
                                    ->send();
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function toggleView(bool $showPrompt): void
    {
        $this->showPromptPreview = $showPrompt;
    }

    public function getMegaPromptProperty(): string
    {
        $formData = $this->form->getState();

        $fileInstructions = '';
        if (!empty($formData['file_rkjm']) || !empty($formData['file_rapor']) || !empty($formData['file_logo'])) {
            $fileInstructions = "\n\n=== DOKUMEN ACUAN TERLAMPIR ===\nSaya telah melampirkan file dokumen berikut pada prompt ini (mohon jadikan acuan utama):";
            if (!empty($formData['file_rkjm'])) $fileInstructions .= "\nDokumen RKJM: [" . (is_array($formData['file_rkjm']) ? array_values($formData['file_rkjm'])[0] : $formData['file_rkjm']) . "]";
            if (!empty($formData['file_rapor'])) $fileInstructions .= "\nHasil Rapor Pendidikan: [" . (is_array($formData['file_rapor']) ? array_values($formData['file_rapor'])[0] : $formData['file_rapor']) . "]";
            if (!empty($formData['file_logo'])) $fileInstructions .= "\nLogo / Lambang Sekolah: [" . (is_array($formData['file_logo']) ? array_values($formData['file_logo'])[0] : $formData['file_logo']) . "]";
            $fileInstructions .= "\n*Tugas Tambahan:* Ekstrak dan integrasikan data dari file terlampir tersebut ke dalam rancangan RKT secara harmonis.";
        }

        return "Bertindaklah sebagai Konsultan Manajemen Pendidikan, Pengawas Sekolah Senior, Tim Penjaminan Mutu Pendidikan, dan Ahli Perencanaan Berbasis Data (PBD). Anda diminta menyusun dokumen Rencana Kerja Tahunan (RKT) Sekolah yang lengkap, sistematis, realistis, terukur, dan terintegrasi secara komprehensif.{$fileInstructions}

=== DATA DASAR SEKOLAH ===
- Nama Sekolah: {$formData['school_name']}
- NPSN: {$formData['npsn']}
- Jenjang: {$formData['jenjang']}
- Status Sekolah: {$formData['status']}
- Tahun Pelajaran: {$formData['tp']}
- Jumlah Peserta Didik: {$formData['siswa']} orang
- Jumlah Guru: {$formData['guru']} orang
- Jumlah Tendik: {$formData['tendik']} orang
- Kepala Sekolah: {$formData['headmaster_name']} (NIP. {$formData['nip_kepsek']})
- Ketua Komite Sekolah: {$formData['komite']}
- Pengawas Sekolah Pembina: {$formData['pengawas']} (NIP. {$formData['nip_pengawas']})

=== DIAGNOSIS RAPOR PENDIDIKAN & EDSP ===
{$formData['masalah_rapor']}

=== PROGRAM PRIORITAS SEKOLAH ===
1. {$formData['prioritas_1']}
2. {$formData['prioritas_2']}
3. {$formData['prioritas_3']}

Tugas Anda adalah memproses seluruh data di atas dan membuat naskah Rencana Kerja Tahunan (RKT) formal yang komprehensif dan profesional berdasarkan sistematika berikut:
- A. HALAMAN AWAL (Cover, Lembar Pengesahan, Kata Pengantar, Daftar Isi)
- B. PENDAHULUAN (Latar Belakang, Dasar Hukum, Tujuan, Manfaat, Sasaran)
- C. ANALISIS KONDISI SEKOLAH (Ringkasan Profil, Rapor Pendidikan, EDSP, Potensi Sekolah)
- D. TUJUAN DAN TARGET KINERJA TAHUNAN (SMART Approach)
- E. PROGRAM DAN KEGIATAN SEKOLAH (17 Bidang Operasional lengkap)
- F. RENCANA AKSI BERBASIS PROGRAM PRIORITAS SEKOLAH
- G. JADWAL PELAKSANAAN KEGIATAN (Kalender Bulanan)
- H. INDIKATOR KINERJA TAHUNAN
- I. MONITORING DAN EVALUASI
- J. RENCANA TINDAK LANJUT (RTL)
- K. PENUTUP";
    }

    public function getProgramsProperty(): array
    {
        $formData = $this->form->getState();
        $isNegeri = $formData['status'] === 'Negeri';
        $sDana = $isNegeri ? 'BOS Reguler' : 'BOP / Yayasan';
        $jen = $formData['jenjang'];
        $guru = $formData['guru'];
        $siswa = $formData['siswa'];

        return [
            [
                'prog' => '1. Kepemimpinan Pembelajaran',
                'keg' => 'Rapat Koordinasi Komunitas Belajar Sekolah & Penyelarasan Kurikulum Satuan Pendidikan berbasis Refleksi Rapor Pendidikan',
                'tuj' => 'Menyamakan visi kepemimpinan pembelajaran yang fokus pada peningkatan kompetensi literasi dan numerasi siswa.',
                'sas' => 'Kepala Sekolah, Tim Pengembang Kurikulum, dan Guru Mata Pelajaran',
                'ind' => 'Dokumen KOSP direvisi dan memuat rencana aksi penuntasan numerasi & literasi secara spesifik.',
                'tar' => '1 Dokumen KOSP yang direvisi; 100% Guru memahami arah target mutu.',
                'pj' => 'Kepala Sekolah',
                'wkt' => 'Juli 2026',
                'dana' => $sDana,
                'bukti' => 'Undangan, Daftar Hadir, Berita Acara, Notulen, Dokumen KOSP Terevisi',
            ],
            [
                'prog' => '2. Implementasi Pembelajaran Mendalam',
                'keg' => 'Workshop Pembelajaran Mendalam (Deep Learning) melalui Strategi Mindful, Meaningful, dan Joyful Learning',
                'tuj' => 'Meningkatkan pemahaman guru dalam merancang metode pembelajaran kontekstif untuk merangsang nalar kritis siswa.',
                'sas' => 'Seluruh Pendidik ' . $jen,
                'ind' => 'Adanya peningkatan kualitas RPP/Modul Ajar yang menerapkan prinsip Deep Learning sebesar 80%.',
                'tar' => "Semua Guru ({$guru} orang) mengumpulkan modul ajar berciri Deep Learning.",
                'pj' => 'Wakasek Kurikulum',
                'wkt' => 'Agustus 2026',
                'dana' => $sDana . ' / Silpa',
                'bukti' => 'Sertifikat Pelatihan, Modul Ajar Terpilih, Foto Kegiatan, Laporan Hasil Evaluasi',
            ],
            [
                'prog' => '3. Penguatan Literasi',
                'keg' => 'Gerakan Sekolah Membaca (GSM) 15 Menit Sebelum Pembelajaran & Pengayaan Sudut Baca Kelas',
                'tuj' => 'Menumbuhkan kebiasaan membaca aktif dan meningkatkan perolehan nilai literasi di Rapor Pendidikan.',
                'sas' => "Seluruh Peserta Didik ({$siswa} siswa)",
                'ind' => 'Peningkatan frekuensi membaca siswa dan tersedianya pojok baca interaktif di semua ruang kelas.',
                'tar' => '100% Kelas memiliki sudut baca; Indeks Literasi Rapor Pendidikan naik minimal 15%.',
                'pj' => 'Koordinator Literasi',
                'wkt' => 'Sepanjang Tahun Ajaran',
                'dana' => $sDana . ' / Kas Paguyuban',
                'bukti' => 'Jurnal Membaca Harian, Foto Pojok Baca, Grafik Perkembangan Literasi',
            ],
            [
                'prog' => '4. Penguatan Numerasi',
                'keg' => 'Inhouse Training (IHT) Desain Alat Peraga Pembelajaran dan Metode Berpikir Kritis Matematika Kontekstual',
                'tuj' => 'Mengatasi kelemahan numerasi sesuai rekomendasi Rapor Pendidikan melalu metode inovatif non-rumus hafalan.',
                'sas' => 'Guru Matematika & Guru Mapel Pendukung',
                'ind' => 'Guru mampu merancang asesmen diagnostik numerasi dan melaksanakan pembelajaran aplikatif matematika.',
                'tar' => '8 Sesi Pelatihan Internal; Nilai Numerasi Rapor Pendidikan mencapai status Hijau.',
                'pj' => 'Wakasek Kurikulum / MGMP Sekolah',
                'wkt' => 'September 2026',
                'dana' => $sDana,
                'bukti' => 'Modul IHT, Dokumentasi Alat Peraga, Hasil Pre/Post-Test Guru',
            ],
            [
                'prog' => '5. Penguatan Karakter',
                'keg' => 'Projek Penguatan Profil Pelajar Pancasila (P5) Bertema Kewirausahaan dan Kebhinnekaan Global',
                'tuj' => 'Membentuk kemandirian, gotong royong, dan akhlak mulia siswa secara terstruktur dalam projek ko-kurikuler.',
                'sas' => 'Peserta Didik Fase ' . ($jen === 'SD' ? 'A/B/C' : ($jen === 'SMP' ? 'D' : 'E/F')),
                'ind' => 'Terlaksananya gelar karya P5 dan pelaporan perkembangan karakter siswa melalui e-Rapor.',
                'tar' => '2 Kegiatan Utama P5 setahun; 100% siswa memiliki portofolio capaian karakter.',
                'pj' => 'Koordinator P5',
                'wkt' => 'Oktober 2026 & Maret 2027',
                'dana' => $sDana,
                'bukti' => 'Modul P5, Laporan Gelar Karya, Raport P5',
            ],
            [
                'prog' => '6. Pengelolaan Kinerja Guru dan Tendik',
                'keg' => 'Supervisi Klinis Kepala Sekolah Berbasis Pengisian Rubrik Observasi Kinerja di Platform Merdeka Mengajar (PMM)',
                'tuj' => 'Mewujudkan keteraturan siklus kinerja ASN maupun non-ASN guna mendukung peningkatan karir dan profesionalisme.',
                'sas' => "Seluruh Pendidik ({$guru} orang)",
                'ind' => 'Tercapainya sasaran kinerja pegawai (SKP) dengan predikat minimal Baik pada sistem PMM.',
                'tar' => '100% Guru disupervisi secara klinis dan didokumentasikan di PMM.',
                'pj' => 'Kepala Sekolah',
                'wkt' => 'November 2026 & April 2027',
                'dana' => 'Mandiri / BOS',
                'bukti' => 'Hasil Tangkapan Layar PMM, Dokumen Supervisi Akademik, SKP Terbitan BKN',
            ],
            [
                'prog' => '7. Pengembangan Kompetensi GTK',
                'keg' => 'Fasilitasi Guru Mengikuti Pelatihan Mandiri PMM, Webinar Nasional, dan Seleksi Guru Penggerak/Pendidikan Profesi Guru',
                'tuj' => 'Mendorong guru memperluas wawasan keilmuan secara eksternal.',
                'sas' => 'Guru dan Tenaga Kependidikan',
                'ind' => 'Adanya peningkatan jumlah guru bersertifikat pendidik atau lulus program pengembangan guru penggerak.',
                'tar' => 'Minimal 30% Guru mendapatkan sertifikat topik PMM per semester.',
                'pj' => 'Urusan Kepegawaian',
                'wkt' => 'Sepanjang Tahun Ajaran',
                'dana' => 'Mandiri / BOS',
                'bukti' => 'Sertifikat PMM, Surat Tugas Pelatihan, Laporan Hasil Diseminasi',
            ],
            [
                'prog' => '8. Komunitas Belajar',
                'keg' => 'Pertemuan Rutin Mingguan Komunitas Belajar (Kombel) "Saling Asuh" Berbasis Siklus Inkuiri Praktik Baik',
                'tuj' => 'Memecahkan kebuntuan pembelajaran harian kelas secara kolektif di antara rekan sejawat.',
                'sas' => 'Seluruh Pendidik',
                'ind' => 'Terjadwalnya diskusi reflektif berkala yang fokus menghasilkan solusi nyata pembelajaran kelas.',
                'tar' => 'Pertemuan Kombel diadakan minimal 2 kali sebulan secara disiplin.',
                'pj' => 'Ketua Kombel Sekolah',
                'wkt' => 'Sepanjang Tahun Ajaran',
                'dana' => 'BOS Reguler (Konsumsi/Bahan)',
                'bukti' => 'Dokumen Praktik Baik, Daftar Hadir Kombel, Notulensi Inkuiri',
            ],
            [
                'prog' => '9. Supervisi Akademik',
                'keg' => 'Penyusunan Rencana, Pelaksanaan, dan Tindak Lanjut Supervisi Kelas oleh Tim Penjaminan Mutu Pendidikan Sekolah',
                'tuj' => 'Memastikan standar proses pembelajaran berlangsung interaktif, mendalam, dan berpihak pada murid.',
                'sas' => 'Semua Guru Kelas',
                'ind' => 'Adanya umpan balik konstruktif yang ditindaklanjuti dengan perbaikan modul ajar serta asesmen.',
                'tar' => '100% Guru disupervisi dua siklus penuh dalam satu tahun pelajaran.',
                'pj' => 'Tim Penjamin Mutu Sekolah',
                'wkt' => 'September 2026 & Februari 2027',
                'dana' => $sDana,
                'bukti' => 'Buku Kendali Supervisi, Lembar Penilaian, Catatan Rekomendasi',
            ],
            [
                'prog' => '10. Penjaminan Mutu Pendidikan',
                'keg' => 'Evaluasi Diri Satuan Pendidikan (EDSP) Tengah Tahun dan Analisis Dampak Kegiatan PBD terhadap Mutu Sekolah',
                'tuj' => 'Memantau efektivitas serapan anggaran ARKAS terhadap indikator Rapor Pendidikan.',
                'sas' => 'Tim Penjaminan Mutu Sekolah (TPMPS)',
                'ind' => 'Laporan keselarasan penggunaan anggaran ARKAS dengan target perbaikan indikator mutu prioritas.',
                'tar' => '1 Dokumen Laporan Hasil EDSP Tengah Tahun.',
                'pj' => 'Ketua TPMPS',
                'wkt' => 'Desember 2026',
                'dana' => $sDana,
                'bukti' => 'Instrumen EDSP Terisi, Lembar Analisis Kesenjangan, Rekomendasi ARKAS',
            ],
            [
                'prog' => '11. Kesiswaan',
                'keg' => 'Layanan Ekstrakurikuler Wajib Pramuka dan Pilihan (Seni, Olahraga, IT) Berbasis Minat dan Bakat Peserta Didik',
                'tuj' => 'Mengakomodasi kebutuhan non-akademik siswa demi perkembangan kesehatan mental dan kreativitas jasmani.',
                'sas' => 'Seluruh Peserta Didik',
                'ind' => 'Aktifnya kegiatan ekstrakurikuler serta raihan prestasi siswa di tingkat kabupaten/provinsi.',
                'tar' => 'Terlaksananya 100% program ekstrakurikuler sesuai jadwal; minimal 3 prestasi diraih.',
                'pj' => 'Wakasek Kesiswaan',
                'wkt' => 'Sepanjang Tahun Ajaran',
                'dana' => $sDana . ' / Komite',
                'bukti' => 'SK Pembina Ekskul, Laporan Bulanan Ekskul, Piagam Penghargaan',
            ],
            [
                'prog' => '12. Pencegahan Perundungan & Kekerasan',
                'keg' => 'Pembentukan, Sosialisasi, dan Aktivasi Tim Pencegahan dan Penanganan Kekerasan (TPPK) Sekolah',
                'tuj' => 'Menciptakan ekosistem sekolah yang steril dari kekerasan fisik, verbal, nonverbal, maupun perundungan siber.',
                'sas' => 'Warga Sekolah (Pendidik, Siswa, Komite, Tenaga Keamanan)',
                'ind' => 'Bebasnya satuan pendidikan dari kasus kekerasan berat dan tuntasnya respon aduan dalam 3x24 jam.',
                'tar' => 'SK TPPK terpasang publik; Tersedianya 1 Kanal Pengaduan Digital terintegrasi Kemendikdasmen.',
                'pj' => 'Ketua TPPK Sekolah',
                'wkt' => 'Juli 2026 (Awal Semester)',
                'dana' => $sDana,
                'bukti' => 'SK TPPK Resmi, Poster Anti Perundungan, Logbook Pengaduan, Berita Acara Kasus',
            ],
            [
                'prog' => '13. Sekolah Ramah Anak',
                'keg' => 'Penerapan Tata Tertib Berbasis Disiplin Positif (Konsekuensi Logis) dan Pendekatan Restitusi',
                'tuj' => 'Mengganti sanksi hukuman fisik yang merusak psikologis anak dengan sistem konsekuensi yang mendidik.',
                'sas' => 'Semua Guru & Peserta Didik',
                'ind' => 'Menurunnya angka pelanggaran tata tertib dan meningkatnya rasa aman siswa di sekolah.',
                'tar' => 'Penyusunan 1 Kesepakatan Kelas di tiap rombel; Nol tindak kekerasan guru ke siswa.',
                'pj' => 'Koordinator BK / Kesiswaan',
                'wkt' => 'Mulai Juli 2026 (Kontinu)',
                'dana' => $sDana,
                'bukti' => 'Dokumen Tata Tertib Baru, Contoh Lembar Segitiga Restitusi BK, Foto Pajangan Kesepakatan Kelas',
            ],
            [
                'prog' => '14. Transformasi Digital Sekolah',
                'keg' => 'Pelatihan Optimalisasi Chromebook Bantuan Pemerintah dan Platform Workspace untuk Pembelajaran Kolaboratif',
                'tuj' => 'Memaksimalkan aset TIK sekolah untuk mendukung proses asesmen nasional dan pembelajaran digital harian.',
                'sas' => 'Guru dan Operator Sekolah',
                'ind' => 'Penggunaan Chromebook yang terstruktur dalam KBM harian di laboratorium komputer maupun kelas.',
                'tar' => 'Pemanfaatan fasilitas TIK minimal 3 kali dalam seminggu untuk kelas bergilir.',
                'pj' => 'Urusan Sarpras / IT Support',
                'wkt' => 'Oktober 2026',
                'dana' => $sDana,
                'bukti' => 'Daftar Inventaris TIK, Logbook Penggunaan Laboratorium, Foto Pembelajaran',
            ],
            [
                'prog' => '15. Pengembangan Sarana dan Prasarana',
                'keg' => 'Pemeliharaan Gedung, Instalasi Listrik, Pengadaan Buku Pengayaan, dan Pemutakhiran Data Inventaris Dapodik',
                'tuj' => 'Menjamin kelayakan dan kenyamanan fisik prasarana belajar demi keselamatan warga sekolah.',
                'sas' => 'Fasilitas Fisik Sekolah',
                'ind' => 'Seluruh sarana dalam kondisi laik fungsi (layak pakai) dan tercatat rapi di sistem Dapodik.',
                'tar' => 'Perawatan berkala rampung sebelum masuk semester genap.',
                'pj' => 'Wakasek Sarana Prasarana',
                'wkt' => 'Desember 2026 (Masa Libur)',
                'dana' => $sDana,
                'bukti' => 'KIB (Kartu Inventaris Barang), Surat Kontrak Kerja Pemeliharaan, Printout Dapodik',
            ],
            [
                'prog' => '16. Kemitraan dan Pelibatan Orang Tua',
                'keg' => 'Pertemuan Rutin Paguyuban Orang Tua Siswa, Kelas Inspirasi, dan Sosialisasi Pola Pengasuhan Positif di Rumah',
                'tuj' => 'Menyamakan persepsi pendidikan anak antara sekolah dan rumah agar tidak kontraproduktif.',
                'sas' => 'Orang Tua/Wali Murid',
                'ind' => 'Kehadiran orang tua dalam rapat koordinasi berkala mencapai di atas 85%.',
                'tar' => '3 Kali Pertemuan Umum per tahun ajaran; Terbentuknya struktur Paguyuban Kelas.',
                'pj' => 'Komite Sekolah / Hubmas',
                'wkt' => 'Juli, Desember 2026 & Juni 2027',
                'dana' => 'Kas Komite / Swadaya',
                'bukti' => 'Daftar Hadir Orang Tua, Notulen Rapat, Materi Sosialisasi Parenting',
            ],
            [
                'prog' => '17. Inovasi dan Kewirausahaan Sekolah',
                'keg' => 'Program Penjualan Karya Kreatif Siswa Melalui Koperasi Sekolah ("Siswa Cerdas Berdikari")',
                'tuj' => 'Melatih kemandirian finansial dan jiwa kewirausahaan siswa melalui praktik ekonomi mikro nyata.',
                'sas' => 'Seluruh Peserta Didik',
                'ind' => 'Siswa mampu mengemas, memasarkan produk hasil projek P5 atau prakarya dalam bazar rutin.',
                'tar' => 'Minimal 2 Kegiatan Bazar atau Pameran Niaga tingkat Sekolah.',
                'pj' => 'Pengelola Koperasi Sekolah',
                'wkt' => 'Desember 2026 (Akhir Semester)',
                'dana' => 'Modal Bergulir Koperasi / Sponsorship',
                'bukti' => 'Laporan Keuangan Bazar, Sampel Kemasan Produk, Dokumentasi Kegiatan',
            ],
        ];
    }
}