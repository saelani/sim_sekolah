@extends('layouts.public')

@section('title', $profile->school_name ?? config('app.name', 'Sekolah'))

@section('page-style')
<style>
    .home-page-wrap {
        background-color: #f8f7f4;
        color: #1e293b;
        padding-bottom: 72px;
    }

    .home-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ================= HERO SECTION ================= */
    .home-hero {
        background: linear-gradient(135deg, #132c47 0%, #1a3a5c 52%, #2c5f8a 100%);
        color: #ffffff;
        padding: 64px 20px 88px;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid #c59b27;
    }

    .home-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(at 20% 20%, rgba(197, 155, 39, 0.14) 0px, transparent 50%),
            radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 100% 100%, 24px 24px;
        pointer-events: none;
    }

    .home-hero-inner {
        max-width: 840px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .home-logo-ring {
        width: 92px;
        height: 92px;
        margin: 0 auto 20px;
        padding: 4px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c59b27 0%, #f8e5a8 50%, #c59b27 100%);
        box-shadow: 0 12px 30px rgba(10, 25, 47, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .home-logo-ring img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .home-logo-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #ffffff;
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .home-ay-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        color: #f8e5a8;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .home-ay-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #4ade80;
        box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.25);
    }

    .home-hero h1 {
        font-size: clamp(1.85rem, 4vw, 2.75rem);
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 14px;
        line-height: 1.2;
        letter-spacing: -0.015em;
    }

    .home-hero .tagline {
        font-size: clamp(0.98rem, 1.8vw, 1.12rem);
        color: rgba(255, 255, 255, 0.88);
        max-width: 660px;
        margin: 0 auto 28px;
        line-height: 1.6;
        font-style: italic;
    }

    .home-hero-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .home-btn-gold {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 12px;
        background: #c59b27;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(197, 155, 39, 0.30);
        transition: all 0.2s ease;
    }

    .home-btn-gold:hover {
        background: #b0881e;
        transform: translateY(-2px);
    }

    .home-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.10);
        border: 1.5px solid rgba(255, 255, 255, 0.32);
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(6px);
        transition: all 0.2s ease;
    }

    .home-btn-outline:hover {
        background: rgba(255, 255, 255, 0.20);
        border-color: #ffffff;
        transform: translateY(-2px);
    }

    /* ================= FLOATING STATS ================= */
    .home-stats-wrap {
        max-width: 1100px;
        margin: -44px auto 0;
        padding: 0 20px;
        position: relative;
        z-index: 5;
    }

    .home-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .home-stat-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 16px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 12px 30px rgba(26, 58, 92, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .home-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 36px rgba(26, 58, 92, 0.12);
    }

    .home-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(26, 58, 92, 0.07);
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .home-stat-icon.gold {
        background: rgba(197, 155, 39, 0.14);
        color: #9e7816;
    }

    .home-stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a3a5c;
        line-height: 1.1;
        font-variant-numeric: tabular-nums;
    }

    .home-stat-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #64748b;
        margin-top: 3px;
    }

    /* ================= SECTION HEADERS ================= */
    .home-section {
        padding-top: 56px;
    }

    .home-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e6e2d8;
    }

    .home-section-kicker {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #9e7816;
        margin-bottom: 4px;
    }

    .home-section-title {
        font-size: 1.55rem;
        font-weight: 800;
        color: #1a3a5c;
        margin: 0;
        line-height: 1.25;
    }

    .home-section-sub {
        font-size: 0.9rem;
        color: #64748b;
        margin: 4px 0 0;
    }

    .home-section-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #dcd7cc;
        color: #1a3a5c;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .home-section-link:hover {
        background: #1a3a5c;
        border-color: #1a3a5c;
        color: #ffffff;
    }

    /* ================= GAMBARAN SEKOLAH ================= */
    .home-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 18px;
    }

    .home-info-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(26, 58, 92, 0.03);
    }

    .home-info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .home-info-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 4px;
    }

    .home-info-value {
        font-size: 0.96rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.5;
        margin: 0;
    }

    .home-accreditation-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 999px;
        background: rgba(26, 58, 92, 0.09);
        color: #1a3a5c;
        font-weight: 800;
        font-size: 0.9rem;
    }

    /* ================= VISI & MISI ================= */
    .home-vm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 22px;
    }

    .home-vm-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 16px;
        padding: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(26, 58, 92, 0.04);
    }

    .home-vm-card.visi {
        border-top: 4px solid #1a3a5c;
    }

    .home-vm-card.misi {
        border-top: 4px solid #c59b27;
    }

    .home-vm-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .home-vm-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a3a5c;
    }

    .home-vm-card p {
        margin: 0;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.7;
    }

    /* ================= BERITA & GALERI CARDS ================= */
    .home-media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 22px;
    }

    .home-media-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 16px rgba(26, 58, 92, 0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .home-media-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(26, 58, 92, 0.10);
    }

    .home-media-thumb {
        position: relative;
        display: block;
        height: 195px;
        overflow: hidden;
        background: #e2e8f0;
        text-decoration: none;
    }

    .home-media-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }

    .home-media-card:hover .home-media-thumb img {
        transform: scale(1.06);
    }

    .home-media-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a3a5c 0%, #2c5f8a 100%);
        color: rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .home-media-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(26, 58, 92, 0.90);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .home-media-badge.bottom-right {
        top: auto;
        left: auto;
        bottom: 12px;
        right: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .home-media-body {
        padding: 18px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .home-media-title {
        font-size: 1.06rem;
        font-weight: 700;
        line-height: 1.4;
        margin: 0 0 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .home-media-title a {
        color: #1e293b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .home-media-title a:hover {
        color: #2c5f8a;
    }

    .home-media-excerpt {
        font-size: 0.88rem;
        color: #64748b;
        line-height: 1.55;
        margin: 0 0 16px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .home-media-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #1a3a5c;
        text-decoration: none;
        margin-top: auto;
        transition: color 0.15s ease, gap 0.15s ease;
    }

    .home-media-cta:hover {
        color: #c59b27;
        gap: 9px;
    }

    /* ================= GURU & TENAGA PENDIDIK ================= */
    .home-teacher-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 18px;
    }

    .home-teacher-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        box-shadow: 0 4px 14px rgba(26, 58, 92, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .home-teacher-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 26px rgba(26, 58, 92, 0.09);
    }

    .home-teacher-top {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .home-teacher-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 2.5px solid #2c5f8a;
        flex-shrink: 0;
        background: #f1f5f9;
    }

    .home-teacher-avatar-fallback {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3a5c, #2c5f8a);
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2.5px solid #c59b27;
        flex-shrink: 0;
    }

    .home-teacher-name {
        font-size: 1.02rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
        line-height: 1.3;
    }

    .home-teacher-role {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 999px;
        background: rgba(197, 155, 39, 0.15);
        color: #967010;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .home-teacher-id {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 4px;
        font-family: monospace;
    }

    .home-teacher-subjects {
        padding-top: 12px;
        border-top: 1px solid #f1efe9;
        font-size: 0.82rem;
        color: #475569;
        line-height: 1.45;
    }

    .home-teacher-subjects strong {
        color: #1a3a5c;
    }

    /* ================= KELAS & MATA PELAJARAN ================= */
    .home-compact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .home-class-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-left: 4px solid #1a3a5c;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(26, 58, 92, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .home-class-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(26, 58, 92, 0.08);
    }

    .home-class-name {
        font-size: 1.04rem;
        font-weight: 700;
        color: #1a3a5c;
        margin-bottom: 4px;
    }

    .home-class-meta {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.45;
    }

    .home-class-badge {
        background: rgba(26, 58, 92, 0.08);
        color: #1a3a5c;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .home-subject-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(26, 58, 92, 0.03);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .home-subject-card:hover {
        transform: translateY(-2px);
        border-color: #c59b27;
    }

    .home-subject-code {
        min-width: 52px;
        height: 44px;
        padding: 0 10px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1a3a5c, #2c5f8a);
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0.03em;
        flex-shrink: 0;
    }

    .home-subject-name {
        font-size: 0.96rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 3px;
    }

    .home-subject-cat {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Empty Box */
    .home-empty-box {
        grid-column: 1 / -1;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 32px 20px;
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
    }

    @media (max-width: 900px) {
        .home-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 520px) {
        .home-hero {
            padding: 48px 16px 76px;
        }

        .home-stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="home-page-wrap">
    {{-- ================= HERO BANNER ================= --}}
    <section class="home-hero">
        <div class="home-hero-inner">
            <div class="home-logo-ring">
                @if ($profile?->logo_path)
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->school_name }}">
                @else
                    <div class="home-logo-fallback">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                @endif
            </div>

            @if ($active)
                <div class="home-ay-pill">
                    <span class="home-ay-dot"></span>
                    <span>Tahun Ajaran Aktif {{ $active->year }} · Semester {{ $active->semester }}</span>
                </div>
            @endif

            <h1>{{ $profile->school_name ?? 'Selamat Datang di Web Sekolah' }}</h1>
            <p class="tagline">“{{ $profile->vision ?? 'Menghasilkan generasi unggul dan berkarakter' }}”</p>

            <div class="home-hero-actions">
                <a href="#gambaran-sekolah" class="home-btn-gold">
                    <span>Profil Sekolah</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
                    </svg>
                </a>
                <a href="{{ route('public.news') }}" class="home-btn-outline">
                    <span>Berita Terbaru</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ================= STATISTIK SEKOLAH ================= --}}
    <div class="home-stats-wrap">
        <div class="home-stats-grid">
            <div class="home-stat-card">
                <div class="home-stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <div class="home-stat-number">{{ number_format($stats['students'] ?? 0) }}</div>
                    <div class="home-stat-label">Peserta Didik</div>
                </div>
            </div>

            <div class="home-stat-card">
                <div class="home-stat-icon gold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <div class="home-stat-number">{{ number_format($stats['teachers'] ?? 0) }}</div>
                    <div class="home-stat-label">Guru & Tendik</div>
                </div>
            </div>

            <div class="home-stat-card">
                <div class="home-stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <div class="home-stat-number">{{ number_format($stats['classes'] ?? 0) }}</div>
                    <div class="home-stat-label">Rombongan Belajar</div>
                </div>
            </div>

            <div class="home-stat-card">
                <div class="home-stat-icon gold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="home-stat-number">{{ number_format($stats['subjects'] ?? 0) }}</div>
                    <div class="home-stat-label">Mata Pelajaran</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= GAMBARAN SEKOLAH ================= --}}
    <section class="home-container home-section" id="gambaran-sekolah">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Identitas Lembaga</span>
                <h2 class="home-section-title">Gambaran Sekolah</h2>
                <p class="home-section-sub">Mengenal lebih dekat profil dan informasi operasional sekolah kami.</p>
            </div>
            <a href="{{ route('public.about') }}" class="home-section-link">
                <span>Selengkapnya</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-info-grid">
            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="9" x2="17" y2="9"/><line x1="7" y1="13" x2="13" y2="13"/></svg>
                </div>
                <div>
                    <div class="home-info-label">NPSN / NISN</div>
                    <p class="home-info-value">{{ $profile->npsn ?? '-' }}</p>
                </div>
            </div>

            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <div class="home-info-label">Kepala Sekolah</div>
                    <p class="home-info-value">{{ $profile->headmaster_name ?? '-' }}</p>
                </div>
            </div>

            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                </div>
                <div>
                    <div class="home-info-label">Akreditasi</div>
                    <p class="home-info-value">
                        <span class="home-accreditation-badge">{{ $profile->accreditation ?? '-' }}</span>
                    </p>
                </div>
            </div>

            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <div class="home-info-label">Tahun Ajaran Aktif</div>
                    <p class="home-info-value">
                        @if ($active)
                            {{ $active->year }} (Semester {{ $active->semester }})
                        @else
                            Belum diatur
                        @endif
                    </p>
                </div>
            </div>

            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <div class="home-info-label">Alamat Sekolah</div>
                    <p class="home-info-value" style="white-space: pre-line;">{{ $profile->address ?? '-' }}</p>
                </div>
            </div>

            <div class="home-info-card">
                <div class="home-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <div class="home-info-label">Telepon / Email</div>
                    <p class="home-info-value">{{ $profile->phone ?? '-' }}<br><span style="font-weight: 500; color: #2c5f8a;">{{ $profile->email ?? '-' }}</span></p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= VISI & MISI ================= --}}
    <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Arah & Tujuan</span>
                <h2 class="home-section-title">Visi & Misi Sekolah</h2>
            </div>
        </div>

        <div class="home-vm-grid">
            <div class="home-vm-card visi">
                <div class="home-vm-header">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1a3a5c" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <h3>Visi</h3>
                </div>
                <p>{{ $profile->vision ?? 'Mencetak generasi unggul, berakhlak mulia, dan berdaya saing tinggi.' }}</p>
            </div>

            <div class="home-vm-card misi">
                <div class="home-vm-header">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#c59b27" stroke-width="2.2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <h3>Misi</h3>
                </div>
                <p style="white-space: pre-line;">{{ $profile->mission ?? 'Menyelenggarakan pembelajaran bermutu, membina karakter, dan menyediakan fasilitas yang mendukung.' }}</p>
            </div>
        </div>
    </section>

    {{-- ================= BERITA TERBARU ================= --}}
    <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Kabar Sekolah</span>
                <h2 class="home-section-title">Berita Terbaru</h2>
                <p class="home-section-sub">Informasi terkini, prestasi, dan kegiatan di lingkungan sekolah.</p>
            </div>
            <a href="{{ route('public.news') }}" class="home-section-link">
                <span>Semua Berita</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-media-grid">
            @forelse ($latestNews as $post)
                <article class="home-media-card">
                    <a href="{{ route('public.news.show', $post->slug) }}" class="home-media-thumb">
                        @if ($post->category)
                            <span class="home-media-badge">{{ $post->category->name }}</span>
                        @endif

                        @if (!empty($post->thumbnail))
                            <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" loading="lazy">
                        @else
                            <div class="home-media-fallback">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                                <span>BERITA SEKOLAH</span>
                            </div>
                        @endif
                    </a>

                    <div class="home-media-body">
                        <h3 class="home-media-title">
                            <a href="{{ route('public.news.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="home-media-excerpt">{{ $post->excerpt }}</p>
                        <a href="{{ route('public.news.show', $post->slug) }}" class="home-media-cta">
                            <span>Baca Selengkapnya</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="home-empty-box">Belum ada berita yang dipublikasikan.</div>
            @endforelse
        </div>
    </section>

    {{-- ================= GALERI FOTO ================= --}}
    <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Dokumentasi</span>
                <h2 class="home-section-title">Galeri Foto</h2>
                <p class="home-section-sub">Rekam jejak kegiatan dan momen berharga warga sekolah.</p>
            </div>
            <a href="{{ route('public.gallery') }}" class="home-section-link">
                <span>Semua Galeri</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-media-grid">
            @forelse ($latestGalleries as $g)
                @php
                    $firstItem = $g->GalleryItem->first();
                    $hasImage = $firstItem && $firstItem->type === 'image' && !empty($firstItem->file_or_url);
                    $photoCount = $g->items_count ?? $g->GalleryItem->count();
                @endphp

                <article class="home-media-card">
                    <a href="{{ route('public.gallery.show', $g->slug) }}" class="home-media-thumb">
                        @if ($hasImage)
                            <img src="{{ $firstItem->file_or_url }}" alt="{{ $g->title }}" loading="lazy">
                        @else
                            <div class="home-media-fallback">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>ALBUM GALERI</span>
                            </div>
                        @endif
                        <span class="home-media-badge bottom-right">{{ $photoCount }} Foto</span>
                    </a>

                    <div class="home-media-body">
                        <h3 class="home-media-title">
                            <a href="{{ route('public.gallery.show', $g->slug) }}">{{ $g->title }}</a>
                        </h3>
                        <p class="home-media-excerpt">{{ $g->description ?: 'Dokumentasi kegiatan ' . $g->title . '.' }}</p>
                        <a href="{{ route('public.gallery.show', $g->slug) }}" class="home-media-cta">
                            <span>Lihat Galeri</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="home-empty-box">Belum ada album galeri yang dipublikasikan.</div>
            @endforelse
        </div>
    </section>

    {{-- ================= GURU & TENAGA PENDIDIK ================= --}}
    <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Sumber Daya Manusia</span>
                <h2 class="home-section-title">Guru & Tenaga Pendidik</h2>
                <p class="home-section-sub">Pendidik dan tenaga kependidikan yang membimbing peserta didik.</p>
            </div>
            <a href="{{ route('public.teachers') }}" class="home-section-link">
                <span>Semua Guru</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-teacher-grid">
            @forelse ($teachers as $t)
                @php
                    $initials = collect(explode(' ', trim($t->name ?? 'G')))
                        ->filter()
                        ->take(2)
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->join('');
                    $idParts = array_filter([
                        !empty($t->nip) ? 'NIP. ' . $t->nip : null,
                        !empty($t->nuptk) ? 'NUPTK. ' . $t->nuptk : null,
                    ]);
                @endphp

                <div class="home-teacher-card">
                    <div class="home-teacher-top">
                        @if (!empty($t->photo_path))
                            <img src="{{ asset('storage/' . $t->photo_path) }}" alt="{{ $t->name }}" class="home-teacher-avatar" loading="lazy">
                        @else
                            <div class="home-teacher-avatar-fallback">{{ $initials ?: 'G' }}</div>
                        @endif

                        <div>
                            <h3 class="home-teacher-name">{{ $t->name ?? '-' }}</h3>
                            <span class="home-teacher-role">{{ ucfirst($t->role_type ?? 'Karyawan') }}</span>
                            @if (!empty($idParts))
                                <div class="home-teacher-id">{{ implode(' · ', $idParts) }}</div>
                            @endif
                        </div>
                    </div>

                    @if ($t->teacherSubjectClass->isNotEmpty())
                        <div class="home-teacher-subjects">
                            <strong>Mapel:</strong> {{ $t->teacherSubjectClass->pluck('subject.name')->unique()->filter()->join(', ') }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="home-empty-box">Belum ada data guru yang ditampilkan.</div>
            @endforelse
        </div>
    </section>

    {{-- ================= KELAS (ROMBONGAN BELAJAR) ================= --}}
    <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Akademik</span>
                <h2 class="home-section-title">Kelas & Rombongan Belajar</h2>
                <p class="home-section-sub">Daftar rombongan belajar aktif beserta wali kelas.</p>
            </div>
            <a href="{{ route('public.classes') }}" class="home-section-link">
                <span>Semua Kelas</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-compact-grid">
            @forelse ($classes as $c)
                <div class="home-class-card">
                    <div>
                        <div class="home-class-name">{{ $c->name }}</div>
                        <div class="home-class-meta">
                            @if ($c->academicYear)
                                TA {{ $c->academicYear->year }} / Sem. {{ $c->academicYear->semester }}
                            @endif
                            @if ($c->homeroomTeacher)
                                <br>Wali Kelas: <strong>{{ $c->homeroomTeacher->name }}</strong>
                            @endif
                        </div>
                    </div>
                    <span class="home-class-badge">{{ $c->students_count ?? 0 }} Siswa</span>
                </div>
            @empty
                <div class="home-empty-box">Belum ada data kelas.</div>
            @endforelse
        </div>
    </section>

    {{-- ================= MATA PELAJARAN ================= --}}
    <!-- <section class="home-container home-section">
        <div class="home-section-head">
            <div>
                <span class="home-section-kicker">Kurikulum</span>
                <h2 class="home-section-title">Mata Pelajaran</h2>
                <p class="home-section-sub">Struktur mata pelajaran yang diajarkan di sekolah.</p>
            </div>
            <a href="{{ route('public.subjects') }}" class="home-section-link">
                <span>Semua Mapel</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <div class="home-compact-grid">
            @forelse ($subjects as $s)
                <div class="home-subject-card">
                    <div class="home-subject-code">{{ $s->code ?: 'MP' }}</div>
                    <div>
                        <div class="home-subject-name">{{ $s->name ?? '-' }}</div>
                        <div class="home-subject-cat">
                            Kode: {{ $s->code ?? '-' }}
                            @if (!empty($s->category))
                                · {{ ucfirst($s->category) }}
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="home-empty-box">Belum ada data mata pelajaran.</div>
            @endforelse
        </div>
    </section> -->
</div>
@endsection