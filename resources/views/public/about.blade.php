@extends('layouts.public')

@section('title', 'Tentang Kami | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('content')
<div class="portal-page-wrap">
    {{-- ================= HERO BANNER ================= --}}
    <header class="portal-hero portal-hero--center about-hero-extended">
        <div class="portal-hero-inner">
            @if ($profile?->logo_path)
                <div class="about-logo-ring">
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->school_name }}">
                </div>
            @endif

            <span class="portal-badge-top">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Profil & Identitas Lembaga
            </span>

            <h1>Tentang Kami</h1>
            <p class="portal-hero-sub">{{ $profile->school_name ?? config('app.name', 'Sekolah') }}</p>
        </div>
    </header>

    {{-- ================= FLOATING IDENTITY HIGHLIGHTS ================= --}}
    <div class="about-identity-wrap">
        <div class="about-identity-grid">
            <div class="portal-card about-id-card">
                <div class="home-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="9" x2="17" y2="9"/><line x1="7" y1="13" x2="13" y2="13"/>
                    </svg>
                </div>
                <div>
                    <div class="home-info-label">Nomor Pokok Sekolah (NPSN)</div>
                    <div class="about-id-value">{{ $profile->npsn ?? '-' }}</div>
                </div>
            </div>

            <div class="portal-card about-id-card">
                <div class="home-stat-icon gold">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                    </svg>
                </div>
                <div>
                    <div class="home-info-label">Status Akreditasi</div>
                    <div class="about-id-value">{{ $profile->accreditation ?? '-' }}</div>
                </div>
            </div>

            <div class="portal-card about-id-card">
                <div class="home-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <div class="home-info-label">Kepala Sekolah</div>
                    <div class="about-id-value">{{ $profile->headmaster_name ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="portal-container">
        {{-- ================= VISI & MISI ================= --}}
        <section class="portal-section">
            <div class="portal-section-head">
                <div>
                    <span class="portal-section-kicker">Landasan & Arah Pendidikan</span>
                    <h2 class="portal-section-title">Visi & Misi Sekolah</h2>
                </div>
            </div>

            <div class="home-vm-grid">
                <div class="portal-card home-vm-card visi">
                    <div class="home-vm-header">
                        <div class="about-vm-icon">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <h2>Visi</h2>
                    </div>
                    <p>{{ $profile->vision ?? 'Mewujudkan pendidikan yang berkualitas dan merata.' }}</p>
                </div>

                <div class="portal-card home-vm-card misi">
                    <div class="home-vm-header">
                        <div class="about-vm-icon">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <h2>Misi</h2>
                    </div>
                    <p style="white-space: pre-line;">{{ $profile->mission ?? 'Meningkatkan mutu pendidikan melalui proses pembelajaran yang inovatif.' }}</p>
                </div>
            </div>
        </section>

        {{-- ================= SEJARAH SINGKAT ================= --}}
        <section class="portal-section">
            <div class="portal-card about-history-card">
                <div class="about-history-header">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--portal-navy)" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <h2>Sejarah Singkat</h2>
                </div>
                <p class="about-history-text">{{ $profile->history ?? $profile->vision ?? 'Informasi sejarah singkat sekolah belum tersedia.' }}</p>
            </div>
        </section>

        {{-- ================= KONTAK & LOKASI ================= --}}
        <section class="portal-section">
            <div class="portal-section-head">
                <div>
                    <span class="portal-section-kicker">Hubungi Kami</span>
                    <h2 class="portal-section-title">Kontak & Lokasi Sekolah</h2>
                </div>
            </div>

            <div class="about-contact-grid">
                <div class="portal-card about-contact-card">
                    <div class="about-contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <div class="home-info-label">Alamat</div>
                        <div class="about-contact-value" style="white-space: pre-line;">{{ $profile->address ?? '-' }}</div>
                    </div>
                </div>

                <div class="portal-card about-contact-card">
                    <div class="about-contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="home-info-label">Telepon</div>
                        <div class="about-contact-value">
                            @if (!empty($profile->phone))
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone) }}">{{ $profile->phone }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <div class="portal-card about-contact-card">
                    <div class="about-contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="3"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <div>
                        <div class="home-info-label">Email</div>
                        <div class="about-contact-value">
                            @if (!empty($profile->email))
                                <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <div class="portal-card about-contact-card">
                    <div class="about-contact-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="home-info-label">Website</div>
                        <div class="about-contact-value">
                            @if (!empty($profile->website))
                                @php
                                    $websiteUrl = \Illuminate\Support\Str::startsWith($profile->website, ['http://', 'https://'])
                                        ? $profile->website
                                        : 'https://' . $profile->website;
                                @endphp
                                <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer">{{ $profile->website }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection