@php
    $profile = $profile ?? \App\Models\SchoolProfile::first();
    $schoolName = $profile->school_name ?? config('app.name', 'Sekolah');
    $logoPath = $profile?->logo_path;
@endphp

<style>
    .public-footer {
        background: linear-gradient(135deg, #132c47 0%, #1a3a5c 65%, #20456c 100%);
        color: rgba(255, 255, 255, 0.86);
        border-top: 4px solid var(--portal-gold, #c59b27);
        position: relative;
        overflow: hidden;
        margin-top: auto;
        font-size: 0.9rem;
    }

    .public-footer::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .public-footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 48px 20px 36px;
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1.35fr 1fr 1.15fr;
        gap: 36px;
    }

    /* Kolom 1: Identitas Sekolah */
    .public-footer-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 14px;
    }

    .public-footer-logo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        padding: 2.5px;
        background: linear-gradient(135deg, #c59b27, #f8e5a8);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
    }

    .public-footer-logo img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .public-footer-logo-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #ffffff;
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .public-footer-school {
        font-size: 1.12rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.3;
        margin: 0;
    }

    .public-footer-sub {
        font-size: 0.72rem;
        font-weight: 700;
        color: #f8e5a8;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .public-footer-desc {
        font-size: 0.87rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.65;
        margin: 0 0 16px;
    }

    .public-footer-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .public-footer-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 11px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #f8e5a8;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Heading Kolom */
    .public-footer-heading {
        font-size: 0.92rem;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.14);
        position: relative;
    }

    .public-footer-heading::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -1px;
        width: 36px;
        height: 2.5px;
        background: #c59b27;
        border-radius: 99px;
    }

    /* Kolom 2: Tautan Cepat */
    .public-footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px 16px;
    }

    .public-footer-links a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.80);
        text-decoration: none;
        font-size: 0.86rem;
        transition: color 0.18s ease, transform 0.18s ease;
    }

    .public-footer-links a:hover {
        color: #f8e5a8;
        transform: translateX(3px);
    }

    /* Kolom 3: Kontak */
    .public-footer-contact {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .public-footer-contact li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.86rem;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.5;
    }

    .public-footer-contact svg {
        color: #f8e5a8;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .public-footer-contact a {
        color: rgba(255, 255, 255, 0.88);
        text-decoration: none;
        transition: color 0.15s ease;
        word-break: break-word;
    }

    .public-footer-contact a:hover {
        color: #f8e5a8;
        text-decoration: underline;
    }

    /* Bottom Bar */
    .public-footer-bottom {
        background: rgba(11, 25, 42, 0.45);
        border-top: 1px solid rgba(255, 255, 255, 0.10);
        position: relative;
        z-index: 1;
    }

    .public-footer-bottom-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.72);
    }

    .public-footer-bottom strong {
        color: #f8e5a8;
        font-weight: 600;
    }

    .public-footer-admin-link {
        color: rgba(255, 255, 255, 0.72);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.15s ease;
    }

    .public-footer-admin-link:hover {
        color: #f8e5a8;
    }

    @media (max-width: 920px) {
        .public-footer-inner {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .public-footer-inner {
            grid-template-columns: 1fr;
            gap: 28px;
            padding: 36px 16px 28px;
        }

        .public-footer-bottom-inner {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<footer class="public-footer">
    <div class="public-footer-inner">
        {{-- Kolom 1: Identitas Sekolah --}}
        <div>
            <div class="public-footer-brand">
                <div class="public-footer-logo">
                    @if ($logoPath)
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $schoolName }}">
                    @else
                        <div class="public-footer-logo-fallback">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="public-footer-school">{{ $schoolName }}</h3>
                    <span class="public-footer-sub">Portal Informasi Akademik</span>
                </div>
            </div>

            <p class="public-footer-desc">
                {{ \Illuminate\Support\Str::limit($profile->vision ?? 'Mewujudkan lembaga pendidikan yang unggul, berkarakter, dan berdaya saing.', 140) }}
            </p>

            @if (!empty($profile?->npsn) || !empty($profile?->accreditation))
                <div class="public-footer-badges">
                    @if (!empty($profile->npsn))
                        <span class="public-footer-pill">NPSN: {{ $profile->npsn }}</span>
                    @endif
                    @if (!empty($profile->accreditation))
                        <span class="public-footer-pill">Akreditasi: {{ $profile->accreditation }}</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Kolom 2: Navigasi Cepat --}}
        <div>
            <h4 class="public-footer-heading">Menu Portal</h4>
            <ul class="public-footer-links">
                <li><a href="{{ route('public.home') }}">Beranda</a></li>
                <li><a href="{{ route('public.news') }}">Berita & Artikel</a></li>
                <li><a href="{{ route('public.gallery') }}">Galeri Foto</a></li>
                <li><a href="{{ route('public.about') }}">Tentang Kami</a></li>
                <li><a href="{{ route('public.teachers') }}">Guru & Pengajar</a></li>
                <li><a href="{{ route('public.classes') }}">Data Kelas</a></li>
                <li><a href="{{ route('public.subjects') }}">Mata Pelajaran</a></li>
            </ul>
        </div>

        {{-- Kolom 3: Kontak & Alamat --}}
        <div>
            <h4 class="public-footer-heading">Hubungi Kami</h4>
            <ul class="public-footer-contact">
                @if (!empty($profile?->address))
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>{{ $profile->address }}</span>
                    </li>
                @endif

                @if (!empty($profile?->phone))
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone) }}">{{ $profile->phone }}</a>
                    </li>
                @endif

                @if (!empty($profile?->email))
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="3"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                    </li>
                @endif

                @if (!empty($profile?->website))
                    @php
                        $websiteUrl = \Illuminate\Support\Str::startsWith($profile->website, ['http://', 'https://'])
                            ? $profile->website
                            : 'https://' . $profile->website;
                    @endphp
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer">{{ $profile->website }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="public-footer-bottom">
        <div class="public-footer-bottom-inner">
            <span>&copy; {{ date('Y') }} <strong>{{ $schoolName }}</strong>. All rights reserved.</span>
            <a href="{{ url('/admin') }}" class="public-footer-admin-link">Panel Administrasi &rarr;</a>
        </div>
    </div>
</footer>