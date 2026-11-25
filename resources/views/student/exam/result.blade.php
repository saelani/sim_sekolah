@php
    $profile = $profile ?? \App\Models\SchoolProfile::first();
    $schoolName = $profile->school_name ?? $schoolName ?? config('app.name', 'Sekolah');
    $subjectName = $exam->subject?->name ?? $exam->Subject?->name ?? '-';
    $finalScore = $score ?? $session->score ?? null;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ujian Selesai | {{ $schoolName }}</title>

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
        .cbt-result-card {
            max-width: 640px;
            margin: 0 auto;
            padding: 36px 28px;
            text-align: center;
            border-top: 4px solid var(--portal-gold, #c59b27);
        }

        .cbt-result-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cbt-result-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--portal-navy, #1a3a5c);
            margin: 0 0 8px;
        }

        .cbt-result-sub {
            font-size: 0.94rem;
            color: var(--portal-muted, #64748b);
            line-height: 1.6;
            margin: 0 auto 24px;
            max-width: 480px;
        }

        .cbt-result-summary {
            background: #fcfbf9;
            border: 1px solid var(--portal-border, #e6e2d8);
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 26px;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 0.9rem;
        }

        .cbt-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .cbt-summary-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .cbt-summary-label {
            color: var(--portal-muted, #64748b);
            font-weight: 600;
        }

        .cbt-summary-val {
            color: var(--portal-text, #1e293b);
            font-weight: 700;
            text-align: right;
        }

        .cbt-score-highlight {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            background: var(--portal-navy, #1a3a5c);
            color: var(--portal-gold-soft, #f8e5a8);
            font-size: 1.05rem;
            font-weight: 800;
        }
    </style>
</head>
<body>
    {{-- Navbar Khusus Siswa (Tanpa Menu Beranda Publik) --}}
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
        </div>
    </nav>

    <main class="portal-page-wrap">
        <section class="portal-container portal-section">
            <div class="portal-card cbt-result-card" style="transform: none;">
                <div class="cbt-result-icon">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>

                <h1 class="cbt-result-title">Jawaban Ujian Berhasil Dikumpulkan</h1>
                <p class="cbt-result-sub">
                    Terima kasih, <strong>{{ $student->name }}</strong>. Jawaban ujian Anda telah tersimpan ke dalam sistem.
                </p>

                <div class="cbt-result-summary">
                    <div class="cbt-summary-row">
                        <span class="cbt-summary-label">Peserta / NIS</span>
                        <span class="cbt-summary-val">{{ $student->name }} ({{ $student->nis }})</span>
                    </div>
                    <div class="cbt-summary-row">
                        <span class="cbt-summary-label">Judul Ujian</span>
                        <span class="cbt-summary-val">{{ $exam->title ?? '-' }}</span>
                    </div>
                    <div class="cbt-summary-row">
                        <span class="cbt-summary-label">Mata Pelajaran</span>
                        <span class="cbt-summary-val">{{ $subjectName }}</span>
                    </div>
                    @if (!is_null($finalScore))
                        <div class="cbt-summary-row" style="align-items: center;">
                            <span class="cbt-summary-label">Nilai Sementara</span>
                            <span class="cbt-score-highlight">{{ $finalScore }}</span>
                        </div>
                    @endif
                </div>

                <a
                    href="{{ route('student.dashboard', ['nis' => $student->nis, 'tab' => 'ulangan']) }}"
                    class="portal-btn-primary"
                    style="padding: 13px 28px; font-size: 0.95rem;"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    <span>Kembali ke Dashboard Siswa</span>
                </a>
            </div>
        </section>
    </main>

    <footer class="student-footer">
        <div class="portal-container">
            <p>&copy; {{ date('Y') }} <span>{{ $schoolName }}</span>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>