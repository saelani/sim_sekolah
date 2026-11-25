@extends('layouts.public')

@section('title', 'Ulangan CBT | ' . ($schoolName ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    /* Kartu Profil Peserta Ujian */
    .cbt-profile-card {
        padding: 22px 26px;
        flex-direction: row;
        align-items: center;
        gap: 22px;
        border-left: 5px solid var(--portal-gold);
        margin-bottom: 36px;
    }

    .cbt-avatar-ring {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        padding: 3px;
        background: linear-gradient(135deg, var(--portal-gold) 0%, var(--portal-gold-soft) 50%, var(--portal-navy-light) 100%);
        box-shadow: 0 6px 18px rgba(26, 58, 92, 0.14);
        flex-shrink: 0;
    }

    .cbt-avatar-ring img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .cbt-avatar-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--portal-navy) 0%, var(--portal-navy-light) 100%);
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cbt-student-name {
        font-size: 1.28rem;
        font-weight: 800;
        color: var(--portal-navy);
        margin: 0 0 8px;
        line-height: 1.25;
    }

    .cbt-student-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .cbt-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .cbt-meta-pill strong {
        color: var(--portal-navy);
    }

    /* Kartu Ujian CBT */
    .cbt-exam-card {
        border-top: 4px solid var(--portal-navy);
    }

    .cbt-exam-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 12px;
    }

    .cbt-subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 8px;
        background: rgba(26, 58, 92, 0.08);
        color: var(--portal-navy);
        font-size: 0.76rem;
        font-weight: 700;
    }

    .cbt-result-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .cbt-exam-title {
        font-size: 1.12rem;
        font-weight: 800;
        color: var(--portal-text);
        margin: 0 0 14px;
        line-height: 1.38;
    }

    .cbt-exam-specs {
        display: flex;
        flex-direction: column;
        gap: 9px;
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid #f1efe9;
        font-size: 0.84rem;
        color: var(--portal-muted);
    }

    .cbt-spec-row {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        line-height: 1.45;
    }

    .cbt-spec-row svg {
        color: var(--portal-navy-light);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .cbt-spec-row strong {
        color: var(--portal-text);
        font-weight: 600;
    }

    @media (max-width: 640px) {
        .cbt-profile-card {
            flex-direction: column;
            text-align: center;
            padding: 22px 18px;
        }

        .cbt-student-meta {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
@php
    $studentInitials = collect(explode(' ', trim($student->name ?? 'S')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->join('');
@endphp

<div class="portal-page-wrap">
    {{-- ================= HERO BANNER ================= --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div>
                <span class="portal-badge-top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                    Computer-Based Test
                </span>
                <h1>Ulangan / CBT</h1>
                <p class="portal-hero-sub">Pilih jadwal ujian yang tersedia untuk mengerjakan soal evaluasi berbasis komputer.</p>
            </div>

            <div class="portal-stat-pill">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8e5a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Tersedia <strong>{{ $exams->count() }}</strong> Ujian</span>
            </div>
        </div>
    </header>

    {{-- ================= KONTEN UTAMA ================= --}}
    <section class="portal-container portal-section">
        {{-- Kartu Identitas Peserta --}}
        <div class="portal-card cbt-profile-card">
            <div class="cbt-avatar-ring">
                @if (!empty($student->photo_path) && file_exists(storage_path('app/public/' . $student->photo_path)))
                    <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto Profil {{ $student->name }}">
                @else
                    <div class="cbt-avatar-fallback">
                        {{ $studentInitials ?: 'S' }}
                    </div>
                @endif
            </div>

            <div style="flex: 1; min-width: 0;">
                <span class="portal-section-kicker">Peserta Ujian</span>
                <h2 class="cbt-student-name">{{ $student->name }}</h2>

                <div class="cbt-student-meta">
                    <span class="cbt-meta-pill">
                        <span>NIS:</span> <strong>{{ $student->nis ?? '-' }}</strong>
                    </span>
                    <span class="cbt-meta-pill">
                        <span>NISN:</span> <strong>{{ $student->nisn ?? '-' }}</strong>
                    </span>
                    <span class="cbt-meta-pill">
                        <span>Kelas:</span> <strong>{{ $classes->name ?? '-' }}</strong>
                    </span>
                    <span class="cbt-meta-pill">
                        <span>Jenis Kelamin:</span> <strong>{{ $student->gender ?? '-' }}</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- Header Daftar Ujian --}}
        <div class="portal-section-head">
            <div>
                <span class="portal-section-kicker">Jadwal Evaluasi</span>
                <h2 class="portal-section-title">Daftar Ujian CBT</h2>
                <p class="portal-section-sub">Pastikan koneksi internet stabil dan perhatikan batas waktu pengerjaan sebelum memulai ujian.</p>
            </div>
        </div>

        {{-- Daftar Kartu Ujian --}}
        <div class="portal-grid">
            @forelse ($exams as $exam)
                <article class="portal-card cbt-exam-card">
                    <div class="portal-card-body">
                        <div class="cbt-exam-header">
                            <span class="cbt-subject-badge">
                                {{ $exam->Subject->code ?? '-' }} &middot; {{ $exam->Subject->name ?? '-' }}
                            </span>

                            @if ($exam->show_result)
                                <span class="cbt-result-badge" title="Nilai langsung ditampilkan setelah ujian selesai">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>Hasil Langsung</span>
                                </span>
                            @endif
                        </div>

                        <h3 class="cbt-exam-title">{{ $exam->title }}</h3>

                        <div class="cbt-exam-specs">
                            <div class="cbt-spec-row">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <span>Durasi Pengerjaan: <strong>{{ $exam->duration_minutes }} menit</strong></span>
                            </div>

                            <div class="cbt-spec-row">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span>
                                    Waktu: <strong>{{ \Illuminate\Support\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}</strong>
                                    s/d <strong>{{ \Illuminate\Support\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <footer class="portal-card-footer">
                        <a href="{{ route('student.exam.take', ['nis' => $student->nis, 'token' => $exam->token, 'id' => $exam->id]) }}" class="portal-btn-primary" style="width: 100%;">
                            <span>Mulai Ujian</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </footer>
                </article>
            @empty
                <div class="portal-empty-state">
                    <div class="portal-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Ujian CBT yang Tersedia</h3>
                    <p>Silakan hubungi guru atau panitia ujian untuk informasi jadwal pelaksanaan ujian.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection