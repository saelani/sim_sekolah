@php
    $profile = $profile ?? \App\Models\SchoolProfile::first();
    $schoolName = $profile->school_name ?? config('app.name', 'Sekolah');
    $studentInitials = collect(explode(' ', trim($student->name ?? 'S')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->join('');
    $hadirCount = $attendances->where('status', 'Hadir')->count();
    $hasFormFeedback = session()->has('status') || session()->has('error') || $errors->any();

    $finalScoresCollection = $finalscores ?? ($finalScore ?? collect());
    $formativesCollection = $formatives ?? ($formatife ?? ($formatifs ?? collect()));
    $sumativesCollection = $sumatives ?? ($sumatif ?? collect());
    $examSessionsCollection = $examSessions ?? ($examSesion ?? ($exam_sessions ?? collect()));
    $ekskulScoresCollection = $extrakurikulerscores ?? ($extrakurikulerScores ?? ($extracurricularScores ?? collect()));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa | {{ $schoolName }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('css/public-portal.css')))
        <link rel="stylesheet" href="{{ asset('css/public-portal.css') }}?v={{ filemtime(public_path('css/public-portal.css')) }}">
    @endif

    @if (file_exists(public_path('css/student-dashboard.css')))
        <link rel="stylesheet" href="{{ asset('css/student-dashboard.css') }}?v={{ filemtime(public_path('css/student-dashboard.css')) }}">
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* Tab bar utama ramah HP */
        .dash-tab-bar {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            padding-bottom: 6px;
        }
        .dash-tab-btn {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        /* Grid ringkasan & kartu */
        .dash-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 16px;
        }

        .pengajuan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }
        .pengajuan-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            background: #fafbfc;
        }
        .pengajuan-box h3 {
            margin: 0 0 6px;
            font-size: 1.05rem;
            color: #111827;
        }
        .permohonan-sub {
            color: #6b7280;
            font-size: .88rem;
            margin: 0 0 16px;
            word-break: break-word;
        }
        .form-row { margin-bottom: 14px; }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: .85rem;
            margin-bottom: 6px;
            color: #1f2937;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            font-family: inherit;
            background: #fff;
        }
        .form-control:focus, .form-select:focus {
            outline: 2px solid #3b82f6;
            border-color: #3b82f6;
        }
        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        .btn-primary-tab {
            background: #1a3a5c;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: .92rem;
            min-height: 42px;
        }
        .btn-primary-tab:hover { background: #142b47; }
        .btn-cancel-tab {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: .92rem;
            min-height: 42px;
        }
        .btn-cancel-tab:hover { background: #e5e7eb; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: .9rem; }
        .alert-success { background: #ecfdf5; color: #15803d; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert ul { margin: 6px 0 0 18px; padding: 0; }

        /* Sub-tab Nilai */
        .nilai-subtabs {
            display: flex;
            flex-wrap: nowrap;
            gap: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .nilai-subtab-btn {
            flex: 0 0 auto;
            white-space: nowrap;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #374151;
            font-size: .86rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .15s ease;
            min-height: 38px;
        }
        .nilai-subtab-btn.active {
            background: #1a3a5c;
            color: #ffffff;
            border-color: #1a3a5c;
        }
        .nilai-subtab-panel { display: none; }
        .nilai-subtab-panel.active { display: block; }
        .nilai-table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .nilai-table {
            width: 100%;
            min-width: 520px;
            border-collapse: collapse;
            font-size: .88rem;
        }
        .nilai-table th, .nilai-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }
        .nilai-table th {
            background: #f8fafc;
            color: #1f2937;
            font-weight: 700;
            white-space: nowrap;
        }

        /* Modal Pratinjau */
        .materi-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.72);
            padding: 16px;
            align-items: center;
            justify-content: center;
        }
        .materi-modal-dialog {
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }
        .materi-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
        }
        .materi-modal-body {
            padding: 16px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            flex: 1;
        }
        .materi-modal-frame {
            width: 100%;
            height: 70vh;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .materi-modal-img {
            display: block;
            max-width: 100%;
            max-height: 70vh;
            margin: 0 auto;
            border-radius: 8px;
            object-fit: contain;
        }

        /* Breakpoint HP */
        @media (max-width: 768px) {
            .dash-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
            .dash-panel-head {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }

        @media (max-width: 600px) {
            .student-navbar-inner {
                padding: 10px 14px;
                gap: 8px;
            }
            .student-brand-name {
                font-size: 0.95rem;
            }
            .student-brand-tag {
                font-size: 0.72rem;
            }
            .btn-student-logout {
                padding: 8px 10px;
                font-size: 0.8rem;
            }
            .portal-hero-inner {
                padding: 20px 14px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .portal-hero h1 {
                font-size: 1.45rem;
            }
            .portal-section {
                padding: 14px 12px 28px;
            }
            .dash-profile-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
                gap: 12px;
            }
            .dash-student-name {
                font-size: 1.15rem;
            }
            .dash-summary-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            .portal-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .dash-panel-body {
                padding: 14px !important;
            }
            .nilai-table {
                min-width: 440px;
                font-size: 0.82rem;
            }
            .nilai-table th, .nilai-table td {
                padding: 8px 10px;
            }
            .materi-modal-backdrop {
                padding: 8px;
            }
            .materi-modal-dialog {
                max-height: 94vh;
                border-radius: 10px;
            }
            .materi-modal-body {
                padding: 12px;
            }
            .materi-modal-frame,
            .materi-modal-img {
                height: 56vh;
                max-height: 56vh;
            }
        }
    </style>
</head>
<body>
    {{-- ================= NAVBAR SISWA ================= --}}
    <nav class="student-navbar">
        <div class="student-navbar-inner">
            <div class="student-brand">
                @if (!empty($profile?->logo_path))
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $schoolName }}" class="student-brand-logo">
                @else
                    <div class="student-brand-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                @endif
                <div>
                    <div class="student-brand-name">{{ $schoolName }}</div>
                    <div class="student-brand-tag">Portal Akademik Siswa</div>
                </div>
            </div>

            <div class="student-nav-actions">
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="btn-student-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="portal-page-wrap">
        {{-- ================= HERO BANNER ================= --}}
        <header class="portal-hero">
            <div class="portal-hero-inner">
                <div>
                    <span class="portal-badge-top">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Area Peserta Didik
                    </span>
                    <h1>Dashboard Siswa</h1>
                    <p class="portal-hero-sub">Selamat datang kembali, <strong>{{ $student->name }}</strong>! Pantau tabungan, presensi, jadwal pelajaran, nilai, dan ujian CBT Anda di sini.</p>
                </div>

                <div class="portal-stat-pill">
                    <span>Kelas Aktif: <strong>{{ $classes->name ?? '-' }}</strong></span>
                </div>
            </div>
        </header>

        {{-- ================= KONTEN UTAMA ================= --}}
        <section class="portal-container portal-section">
            {{-- Kartu Identitas Siswa --}}
            <div class="portal-card dash-profile-card" style="transform: none;">
                <div class="dash-avatar-ring">
                    @if (!empty($student->photo_path) && file_exists(storage_path('app/public/' . $student->photo_path)))
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto Profil {{ $student->name }}">
                    @else
                        <div class="dash-avatar-fallback">{{ $studentInitials ?: 'S' }}</div>
                    @endif
                </div>

                <div style="flex: 1; min-width: 0; width: 100%;">
                    <span class="portal-section-kicker">Biodata Peserta Didik</span>
                    <h2 class="dash-student-name">{{ $student->name }}</h2>

                    <div class="dash-meta-pills">
                        <span class="dash-meta-pill"><span>NIS:</span> <strong>{{ $student->nis ?? '-' }}</strong></span>
                        <span class="dash-meta-pill"><span>NISN:</span> <strong>{{ $student->nisn ?? '-' }}</strong></span>
                        <span class="dash-meta-pill"><span>Kelas:</span> <strong>{{ $classes->name ?? '-' }}</strong></span>
                        <span class="dash-meta-pill"><span>Jenis Kelamin:</span> <strong>{{ $student->gender ?? '-' }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Kartu Ringkasan Cepat --}}
            <div class="dash-summary-grid">
                <div class="portal-card dash-summary-card">
                    <div class="dash-summary-icon gold">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <div class="dash-summary-val">Rp {{ number_format($totalSavings ?? 0, 0, ',', '.') }}</div>
                        <div class="dash-summary-lbl">Total Tabungan</div>
                    </div>
                </div>

                <div class="portal-card dash-summary-card">
                    <div class="dash-summary-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <div class="dash-summary-val">{{ $hadirCount }} Hari</div>
                        <div class="dash-summary-lbl">Kehadiran Tercatat</div>
                    </div>
                </div>

                <div class="portal-card dash-summary-card">
                    <div class="dash-summary-icon gold">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <div class="dash-summary-val">{{ $schedules->count() }} Sesi</div>
                        <div class="dash-summary-lbl">Jadwal Pelajaran</div>
                    </div>
                </div>

                <div class="portal-card dash-summary-card">
                    <div class="dash-summary-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <div class="dash-summary-val">{{ $exams->count() }} Ujian</div>
                        <div class="dash-summary-lbl">Ulangan CBT</div>
                    </div>
                </div>
            </div>

            {{-- Navigasi Tab --}}
            <div class="dash-tab-bar" role="tablist">
                <button type="button" class="dash-tab-btn active" data-tab="tabungan" role="tab" aria-selected="true">
                    <span>Tabungan</span>
                </button>
                <button type="button" class="dash-tab-btn" data-tab="presensi" role="tab" aria-selected="false">
                    <span>Kehadiran</span>
                </button>
                <button type="button" class="dash-tab-btn" data-tab="jadwal" role="tab" aria-selected="false">
                    <span>Jadwal Pelajaran</span>
                </button>
                <button type="button" class="dash-tab-btn" data-tab="ulangan" role="tab" aria-selected="false">
                    <span>Ulangan CBT</span>
                    @if ($exams->isNotEmpty())
                        <span class="dash-tab-count">{{ $exams->count() }}</span>
                    @endif
                </button>
                <button type="button" class="dash-tab-btn" data-tab="nilai" role="tab" aria-selected="false">
                    <span>Nilai</span>
                </button>
                <button type="button" class="dash-tab-btn" data-tab="pengajuan" role="tab" aria-selected="false">
                    <span>Pengajuan</span>
                </button>
                <button type="button" class="dash-tab-btn" data-tab="materi" role="tab" aria-selected="false">
                    <span>Materi & Modul</span>
                    @if ($materials->isNotEmpty())
                        <span class="dash-tab-count">{{ $materials->count() }}</span>
                    @endif
                </button>
            </div>

            {{-- Konten Masing-Masing Tab --}}
            <div class="tab-content">
                @include('student.tabs.tabungan')
                @include('student.tabs.presensi')
                @include('student.tabs.jadwal')
                @include('student.tabs.ulangan')

                {{-- TAB NILAI --}}
                <div class="tab-pane" id="nilai" role="tabpanel">
                    <div class="portal-card" style="transform: none;">
                        <div class="dash-panel-head">
                            <h2>Rekap Nilai Siswa</h2>
                            <span class="dash-panel-meta">{{ $student->name }} · NIS {{ $student->nis ?? '-' }}</span>
                        </div>

                        <div class="dash-panel-body" style="padding: 24px;">
                            <div class="nilai-subtabs" role="tablist" aria-label="Jenis Nilai">
                                <button type="button" class="nilai-subtab-btn active" data-subtab="nilai-formatif" role="tab" aria-selected="true">
                                    Formatif ({{ $formativesCollection->count() }})
                                </button>
                                <button type="button" class="nilai-subtab-btn" data-subtab="nilai-sumatif" role="tab" aria-selected="false">
                                    Sumatif ({{ $sumativesCollection->count() }})
                                </button>
                                <button type="button" class="nilai-subtab-btn" data-subtab="nilai-sesi" role="tab" aria-selected="false">
                                    Sesi Ujian ({{ $examSessionsCollection->count() }})
                                </button>
                                <button type="button" class="nilai-subtab-btn" data-subtab="nilai-final" role="tab" aria-selected="false">
                                    Nilai Akhir ({{ $finalScoresCollection->count() }})
                                </button>
                                <button type="button" class="nilai-subtab-btn" data-subtab="nilai-ekskul" role="tab" aria-selected="false">
                                    Ekstrakurikuler ({{ $ekskulScoresCollection->count() }})
                                </button>
                            </div>

                            {{-- 1. Formatif (grade_formatifs) --}}
                            <div id="nilai-formatif" class="nilai-subtab-panel active">
                                @if ($formativesCollection->isEmpty())
                                    <div class="portal-empty-state" style="border: none; padding: 28px 16px;">
                                        <h3>Belum Ada Nilai Formatif</h3>
                                        <p>Data penilaian formatif belum tersedia untuk akun ini.</p>
                                    </div>
                                @else
                                    <div class="nilai-table-wrap">
                                        <table class="nilai-table">
                                            <thead>
                                                <tr>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Tujuan Pembelajaran (TP)</th>
                                                    <th>Jenis</th>
                                                    <th>Nilai</th>
                                                    <th>Ketercapaian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($formativesCollection as $row)
                                                    @php
                                                        $tpLabel = $row->learningObjective->code
                                                            ?? $row->learningObjective->title
                                                            ?? $row->learningObjective->name
                                                            ?? $row->learningObjective->description
                                                            ?? ('TP #' . ($row->learning_objective_id ?? '-'));
                                                        $mapelLabel = $row->learningObjective->subject->name ?? '-';
                                                        $achieved = $row->is_achieved;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $mapelLabel }}</td>
                                                        <td><strong>{{ $tpLabel }}</strong></td>
                                                        <td>{{ $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-' }}</td>
                                                        <td><strong>{{ $row->score ?? '-' }}</strong></td>
                                                        <td>
                                                            @if (!is_null($achieved))
                                                                @if ((bool) $achieved)
                                                                    <span style="color:#15803d;font-weight:600;">Tercapai</span>
                                                                @else
                                                                    <span style="color:#b91c1c;font-weight:600;">Belum Tercapai</span>
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            {{-- 2. Sumatif (grade_sumatifs) --}}
                            <div id="nilai-sumatif" class="nilai-subtab-panel">
                                @if ($sumativesCollection->isEmpty())
                                    <div class="portal-empty-state" style="border: none; padding: 28px 16px;">
                                        <h3>Belum Ada Nilai Sumatif</h3>
                                        <p>Data penilaian sumatif belum tersedia untuk akun ini.</p>
                                    </div>
                                @else
                                    <div class="nilai-table-wrap">
                                        <table class="nilai-table">
                                            <thead>
                                                <tr>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Lingkup Sumatif</th>
                                                    <th>Jenis</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sumativesCollection as $row)
                                                    @php
                                                        $scopeLabel = $row->sumativeScope->name
                                                            ?? $row->sumativeScope->title
                                                            ?? $row->sumativeScope->scope
                                                            ?? '-';
                                                    @endphp
                                                    <tr>
                                                        <td><strong>{{ $row->subject->name ?? '-' }}</strong></td>
                                                        <td>{{ $scopeLabel }}</td>
                                                        <td>{{ $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-' }}</td>
                                                        <td><strong>{{ $row->score ?? '-' }}</strong></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            {{-- 3. Sesi Ujian (cbt_exam_sessions) --}}
                            <div id="nilai-sesi" class="nilai-subtab-panel">
                                @if ($examSessionsCollection->isEmpty())
                                    <div class="portal-empty-state" style="border: none; padding: 28px 16px;">
                                        <h3>Belum Ada Sesi Ujian</h3>
                                        <p>Riwayat sesi ujian CBT belum tersedia.</p>
                                    </div>
                                @else
                                    <div class="nilai-table-wrap">
                                        <table class="nilai-table">
                                            <thead>
                                                <tr>
                                                    <th>Ujian</th>
                                                    <th>Status</th>
                                                    <th>Skor</th>
                                                    <th>Total Skor</th>
                                                    <th>Waktu Submit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($examSessionsCollection as $session)
                                                    @php
                                                        $submittedRaw = $session->submitted_at ?? $session->start_time;
                                                        $submittedFormatted = '-';
                                                        if (!empty($submittedRaw)) {
                                                            try {
                                                                $submittedFormatted = \Illuminate\Support\Carbon::parse($submittedRaw)->format('d M Y H:i');
                                                            } catch (\Throwable $e) {
                                                                $submittedFormatted = (string) $submittedRaw;
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td><strong>{{ $session->exam->title ?? ($session->exam->name ?? ('Ujian #' . ($session->cbt_exam_id ?? '-'))) }}</strong></td>
                                                        <td>{{ $session->status ? ucfirst(str_replace('_', ' ', $session->status)) : '-' }}</td>
                                                        <td><strong>{{ $session->score ?? '-' }}</strong></td>
                                                        <td>{{ $session->total_score ?? '-' }}</td>
                                                        <td>{{ $submittedFormatted }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            {{-- 4. Nilai Akhir --}}
                            <div id="nilai-final" class="nilai-subtab-panel">
                                @if ($finalScoresCollection->isEmpty())
                                    <div class="portal-empty-state" style="border: none; padding: 28px 16px;">
                                        <h3>Belum Ada Nilai Akhir</h3>
                                        <p>Rekap nilai akhir mata pelajaran belum tersedia.</p>
                                    </div>
                                @else
                                    <div class="nilai-table-wrap">
                                        <table class="nilai-table">
                                            <thead>
                                                <tr>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Nilai Akhir</th>
                                                    <th>Predikat</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($finalScoresCollection as $row)
                                                    <tr>
                                                        <td><strong>{{ $row->subject->name ?? '-' }}</strong></td>
                                                        <td><strong>{{ $row->final_score ?? ($row->score ?? ($row->value ?? '-')) }}</strong></td>
                                                        <td>{{ $row->grade ?? ($row->predikat ?? '-') }}</td>
                                                        <td>{{ $row->notes ?? ($row->description ?? '-') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            {{-- 5. Ekstrakurikuler --}}
                            <div id="nilai-ekskul" class="nilai-subtab-panel">
                                @if ($ekskulScoresCollection->isEmpty())
                                    <div class="portal-empty-state" style="border: none; padding: 28px 16px;">
                                        <h3>Belum Ada Nilai Ekstrakurikuler</h3>
                                        <p>Data nilai atau predikat ekstrakurikuler belum tersedia.</p>
                                    </div>
                                @else
                                    <div class="nilai-table-wrap">
                                        <table class="nilai-table">
                                            <thead>
                                                <tr>
                                                    <th>Ekstrakurikuler</th>
                                                    <th>Predikat / Nilai</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ekskulScoresCollection as $ekskul)
                                                    <tr>
                                                        <td><strong>{{ $ekskul->extracurricular->name ?? ($ekskul->extrakurikuler->name ?? ($ekskul->name ?? '-')) }}</strong></td>
                                                        <td><strong>{{ $ekskul->grade ?? ($ekskul->predikat ?? ($ekskul->score ?? '-')) }}</strong></td>
                                                        <td>{{ $ekskul->notes ?? ($ekskul->description ?? '-') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB PENGAJUAN --}}
                <div class="tab-pane" id="pengajuan" role="tabpanel">
                    <div class="portal-card" style="transform: none;">
                        <div class="dash-panel-head">
                            <h2>Pengajuan Absen & Tabungan</h2>
                            <span class="dash-panel-meta">Kelas {{ $classes->name ?? '-' }}</span>
                        </div>

                        <div class="dash-panel-body" style="padding: 24px;">
                            <p class="permohonan-sub">
                                Siswa: <strong>{{ $student->name }}</strong> ({{ $student->nis }}) · Kelas: <strong>{{ $classes->name ?? '-' }}</strong>
                            </p>

                            @if (session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-error">{{ session('error') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-error">
                                    <strong>Periksa kembali isian formulir:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="pengajuan-grid">
                                @include('student.tabs.pengajuan-absensi')
                                @include('student.tabs.pengajuan-tabungan')
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB MATERI & MODUL --}}
                <div class="tab-pane" id="materi" role="tabpanel">
                    <div class="portal-card" style="transform: none;">
                        <div class="dash-panel-head">
                            <h2>Materi & Modul</h2>
                            <span class="dash-panel-meta">{{ $materials->count() }} modul tersedia</span>
                        </div>

                        <div class="dash-panel-body" style="padding: 24px;">
                            @if ($materials->isEmpty())
                                <div class="portal-empty-state" style="border: none; padding: 32px 16px;">
                                    <h3>Belum Ada Materi</h3>
                                    <p>Modul/citra pembelajaran akan muncul di sini saat guru mengunggah materi aktif.</p>
                                </div>
                            @else
                                <div class="portal-grid">
                                    @foreach ($materials as $mat)
                                        @php
                                            $subjectName = $mat->subject->name ?? '-';
                                            $subjectCode = $mat->subject->code ?? '-';
                                            $typeLabel = $mat->type ? ucfirst(str_replace('_', ' ', trim($mat->type))) : 'Modul';

                                            $isYouTube = false;
                                            $videoId = null;

                                            if (!empty($mat->external_url)) {
                                                $externalUrl = trim($mat->external_url);
                                                $host = strtolower((string) parse_url($externalUrl, PHP_URL_HOST));
                                                $path = (string) parse_url($externalUrl, PHP_URL_PATH);
                                                $query = (string) parse_url($externalUrl, PHP_URL_QUERY);
                                                $segments = array_values(array_filter(explode('/', trim($path, '/'))));

                                                if (str_contains($host, 'youtu.be') && !empty($segments[0])) {
                                                    $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $segments[0]);
                                                    $isYouTube = $videoId !== '';
                                                } elseif (str_contains($host, 'youtube.com') && str_contains($path, 'watch')) {
                                                    parse_str($query, $queryParams);
                                                    if (!empty($queryParams['v']) && is_string($queryParams['v'])) {
                                                        $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $queryParams['v']);
                                                        $isYouTube = $videoId !== '';
                                                    }
                                                } elseif (str_contains($host, 'youtube.com') && !empty($segments[1]) && in_array($segments[0], ['embed', 'shorts'], true)) {
                                                    $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $segments[1]);
                                                    $isYouTube = $videoId !== '';
                                                }
                                            }

                                            $fileUrl = null;
                                            $fileExt = '';
                                            $isImage = false;
                                            $isPdf = false;

                                            if (!empty($mat->file_path)) {
                                                $cleanPath = ltrim($mat->file_path, '/');
                                                $fileUrl = asset('storage/' . $cleanPath);
                                                $fileExt = strtolower((string) pathinfo($cleanPath, PATHINFO_EXTENSION));
                                                $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                                                $isPdf = $fileExt === 'pdf';
                                            }

                                            $plainTextContent = trim((string) ($mat->content ?? ''));

                                            $modalKind = null;
                                            $modalSrc = null;
                                            $buttonText = null;

                                            if ($isYouTube && $videoId) {
                                                $modalKind = 'youtube';
                                                $modalSrc = $videoId;
                                                $buttonText = 'Tonton Video';
                                            } elseif ($isPdf && $fileUrl) {
                                                $modalKind = 'pdf';
                                                $modalSrc = $fileUrl;
                                                $buttonText = 'Baca PDF';
                                            } elseif ($isImage && $fileUrl) {
                                                $modalKind = 'image';
                                                $modalSrc = $fileUrl;
                                                $buttonText = 'Lihat Gambar';
                                            } elseif ($plainTextContent !== '') {
                                                $modalKind = 'text';
                                                $modalSrc = $plainTextContent;
                                                $buttonText = 'Baca Teks';
                                            }
                                        @endphp

                                        <article class="portal-card dash-materi-card">
                                            <div class="portal-card-body">
                                                <div>
                                                    <span class="dash-materi-subject">
                                                        {{ $subjectCode }} · {{ $subjectName }}
                                                    </span>
                                                    @if ($mat->class_level)
                                                        <span class="dash-materi-type">{{ $typeLabel }}</span>
                                                    @endif
                                                </div>

                                                <h3 class="dash-materi-title">{{ $mat->title }}</h3>

                                                <div class="dash-materi-meta">
                                                    @if ($mat->class_level)
                                                        <div>Level: <strong>{{ $mat->class_level }}</strong></div>
                                                    @endif
                                                    @if ($mat->external_url && !$isYouTube)
                                                        <div style="font-size:0.82rem;color:#6b7280;">
                                                            <a href="{{ $mat->external_url }}" target="_blank" rel="noopener noreferrer" style="color:#3b82f6;text-decoration:underline;">
                                                                Buka Link Eksternal ›
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if ($plainTextContent !== '')
                                                    <p style="margin-top: 8px; font-size: 0.86rem; color: #475569; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                        {{ $plainTextContent }}
                                                    </p>
                                                @endif
                                            </div>

                                            <footer class="portal-card-footer">
                                                @if ($modalKind)
                                                    <button
                                                        type="button"
                                                        class="portal-btn-primary js-open-materi-modal"
                                                        style="width: 100%; min-height: 42px;"
                                                        data-kind="{{ $modalKind }}"
                                                        data-title="{{ $mat->title }}"
                                                        data-meta="{{ $subjectCode }} · {{ $subjectName }}"
                                                        data-src="{{ $modalSrc }}"
                                                        data-download-url="{{ $fileUrl ?? '' }}"
                                                    >
                                                        <span>{{ $buttonText }}</span>
                                                    </button>
                                                @elseif ($fileUrl)
                                                    <a
                                                        href="{{ $fileUrl }}"
                                                        class="portal-btn-primary"
                                                        style="width: 100%; min-height: 42px;"
                                                        download
                                                    >
                                                        <span>Unduh ({{ strtoupper($fileExt ?: 'FILE') }})</span>
                                                    </a>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="portal-btn-primary"
                                                        style="width: 100%; min-height: 42px; opacity: 0.55; cursor: not-allowed;"
                                                        disabled
                                                    >
                                                        Belum Ada Isi
                                                    </button>
                                                @endif
                                            </footer>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ================= MODAL PRATINJAU MATERI ================= --}}
    <div
        id="materiPreviewModal"
        class="materi-modal-backdrop"
        role="dialog"
        aria-modal="true"
        aria-labelledby="materiModalTitle"
    >
        <div class="materi-modal-dialog">
            <div class="materi-modal-head">
                <div style="min-width: 0;">
                    <div id="materiModalMeta" style="font-size: 0.76rem; color: #64748b;"></div>
                    <h3 id="materiModalTitle" style="margin: 2px 0 0; font-size: 1rem; color: #0f172a; word-break: break-word;"></h3>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                    <a
                        id="materiModalDownload"
                        href="#"
                        download
                        style="display: none; padding: 7px 11px; font-size: 0.82rem; border-radius: 6px; background: #f1f5f9; color: #0f172a; text-decoration: none; border: 1px solid #cbd5e1;"
                    >
                        Unduh
                    </a>
                    <button
                        type="button"
                        id="materiModalClose"
                        aria-label="Tutup modal"
                        style="width: 36px; height: 36px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #0f172a; font-size: 1.15rem; cursor: pointer;"
                    >
                        ×
                    </button>
                </div>
            </div>

            <div id="materiModalBody" class="materi-modal-body"></div>
        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <footer class="student-footer">
        <div class="portal-container">
            <p>© {{ date('Y') }} <span>{{ $schoolName }}</span>. All rights reserved.</p>
            @php
                $contactItems = array_filter([
                    $profile->address ?? null,
                    $profile->phone ?? null,
                    $profile->email ?? null,
                ]);
            @endphp
            @if (!empty($contactItems))
                <p style="font-size: 0.8rem; opacity: 0.76; margin-top: 6px;">
                    {{ implode(' · ', $contactItems) }}
                </p>
            @endif
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('[data-tab]');
            const panes = document.querySelectorAll('.tab-pane');

            function activateTab(tabName) {
                const targetBtn = document.querySelector('[data-tab="' + tabName + '"]');
                const targetPane = document.getElementById(tabName);
                if (!targetBtn || !targetPane) return;

                tabs.forEach(function (b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                panes.forEach(function (p) {
                    p.classList.remove('active');
                });

                targetBtn.classList.add('active');
                targetBtn.setAttribute('aria-selected', 'true');
                targetPane.classList.add('active');
                targetBtn.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
            }

            tabs.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activateTab(this.dataset.tab);
                });
            });

            const subtabBtns = document.querySelectorAll('.nilai-subtab-btn');
            const subtabPanels = document.querySelectorAll('.nilai-subtab-panel');
            subtabBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-subtab');
                    subtabBtns.forEach(function (b) {
                        const isMatch = b === btn;
                        b.classList.toggle('active', isMatch);
                        b.setAttribute('aria-selected', isMatch ? 'true' : 'false');
                    });
                    subtabPanels.forEach(function (panel) {
                        panel.classList.toggle('active', panel.id === targetId);
                    });
                    btn.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
                });
            });

            const modal = document.getElementById('materiPreviewModal');
            const modalTitle = document.getElementById('materiModalTitle');
            const modalMeta = document.getElementById('materiModalMeta');
            const modalBody = document.getElementById('materiModalBody');
            const modalDownload = document.getElementById('materiModalDownload');
            const closeBtn = document.getElementById('materiModalClose');

            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                modalBody.innerHTML = '';
                modalDownload.style.display = 'none';
                modalDownload.setAttribute('href', '#');
            }

            function openModal(btn) {
                const kind = btn.getAttribute('data-kind');
                const title = btn.getAttribute('data-title') || 'Materi';
                const meta = btn.getAttribute('data-meta') || '';
                const src = btn.getAttribute('data-src') || '';
                const downloadUrl = btn.getAttribute('data-download-url') || '';

                modalTitle.textContent = title;
                modalMeta.innerHTML = meta;
                modalBody.innerHTML = '';

                if (downloadUrl) {
                    modalDownload.href = downloadUrl;
                    modalDownload.style.display = 'inline-block';
                } else {
                    modalDownload.style.display = 'none';
                }

                if (kind === 'youtube') {
                    const wrap = document.createElement('div');
                    wrap.style.cssText = 'position:relative;width:100%;aspect-ratio:16/9;background:#000;border-radius:8px;overflow:hidden;';

                    const iframe = document.createElement('iframe');
                    iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(src) + '?rel=0&autoplay=1';
                    iframe.title = title;
                    iframe.style.cssText = 'width:100%;height:100%;border:0;';
                    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                    iframe.referrerPolicy = 'strict-origin-when-cross-origin';
                    iframe.allowFullscreen = true;

                    wrap.appendChild(iframe);
                    modalBody.appendChild(wrap);
                } else if (kind === 'pdf') {
                    const iframe = document.createElement('iframe');
                    iframe.src = src + '#toolbar=1';
                    iframe.title = title;
                    iframe.className = 'materi-modal-frame';
                    modalBody.appendChild(iframe);
                } else if (kind === 'image') {
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = title;
                    img.className = 'materi-modal-img';
                    modalBody.appendChild(img);
                } else if (kind === 'text') {
                    const textBox = document.createElement('div');
                    textBox.style.cssText = 'font-size:0.95rem;line-height:1.7;color:#1e293b;white-space:pre-line;';
                    textBox.textContent = src;
                    modalBody.appendChild(textBox);
                }

                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            document.addEventListener('click', function (e) {
                const trigger = e.target.closest('.js-open-materi-modal');
                if (trigger) {
                    e.preventDefault();
                    openModal(trigger);
                    return;
                }

                if (e.target === modal) {
                    closeModal();
                }
            });

            closeBtn.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });

            document.querySelectorAll('[data-reset-form]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const form = document.getElementById(this.dataset.resetForm);
                    if (form) {
                        form.reset();
                    }
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            const requestedTab = urlParams.get('tab');
            if (requestedTab) {
                activateTab(requestedTab);
            } else if (@json($hasFormFeedback)) {
                activateTab('pengajuan');
            }
        });
    </script>
</body>
</html>