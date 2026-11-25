@php
    $profile = $profile ?? \App\Models\SchoolProfile::first();
    $schoolName = $profile->school_name ?? config('app.name', 'Sekolah');
    $email = $profile->email ?? 'info@sekolah.com';
    $logoPath = $profile?->logo_path;
@endphp

<style>
    .public-navbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #e6e2d8;
        transition: box-shadow 0.25s ease, background-color 0.25s ease;
    }

    .public-navbar.is-scrolled {
        box-shadow: 0 8px 28px rgba(26, 58, 92, 0.08);
        background: rgba(255, 255, 255, 0.98);
    }

    .public-navbar-accent {
        height: 3px;
        background: linear-gradient(90deg, #1a3a5c 0%, #2c5f8a 65%, #c59b27 100%);
    }

    .public-nav-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 20px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    /* Brand / Logo Sekolah */
    .public-nav-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        min-width: 0;
        flex-shrink: 0;
    }

    .public-nav-logo-wrap {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, #1a3a5c, #c59b27);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(26, 58, 92, 0.14);
        transition: transform 0.2s ease;
    }

    .public-nav-brand:hover .public-nav-logo-wrap {
        transform: scale(1.04);
    }

    .public-nav-logo-wrap img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
    }

    .public-nav-logo-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #ffffff;
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .public-nav-title-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .public-nav-school-name {
        font-size: 1.02rem;
        font-weight: 700;
        color: #1a3a5c;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 230px;
    }

    .public-nav-school-sub {
        font-size: 0.7rem;
        font-weight: 600;
        color: #8c7335;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    /* Daftar Menu Tengah */
    .public-nav-links {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 4px;
    }

    .public-nav-links li a {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .public-nav-links li a:hover {
        color: #1a3a5c;
        background-color: rgba(26, 58, 92, 0.05);
    }

    .public-nav-links li a.is-active {
        color: #1a3a5c;
        font-weight: 700;
        background-color: rgba(26, 58, 92, 0.06);
    }

    .public-nav-links li a.is-active::after {
        content: '';
        position: absolute;
        left: 12px;
        right: 12px;
        bottom: 3px;
        height: 2.5px;
        border-radius: 99px;
        background: #c59b27;
    }

    /* Tombol Aksi Kanan */
    .public-nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .btn-nav-contact {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #1a3a5c;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .btn-nav-contact:hover {
        border-color: #1a3a5c;
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .btn-nav-admin {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #1a3a5c 0%, #265380 100%);
        color: #ffffff !important;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(26, 58, 92, 0.20);
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .btn-nav-admin:hover {
        background: linear-gradient(135deg, #142e49 0%, #1f456b 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(26, 58, 92, 0.26);
    }

    /* Tombol Hamburger Mobile */
    .public-nav-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #1a3a5c;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .public-nav-toggle:hover {
        background: #f8fafc;
    }

    /* Panel Menu Mobile */
    .public-mobile-drawer {
        display: none;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        padding: 14px 20px 20px;
        box-shadow: 0 14px 30px rgba(26, 58, 92, 0.10);
    }

    .public-mobile-drawer.is-open {
        display: block;
    }

    .public-mobile-links {
        list-style: none;
        margin: 0 0 14px;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .public-mobile-links li a {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #334155;
        text-decoration: none;
        background: #f8fafc;
        transition: all 0.15s ease;
    }

    .public-mobile-links li a:hover,
    .public-mobile-links li a.is-active {
        background: rgba(26, 58, 92, 0.08);
        color: #1a3a5c;
        font-weight: 700;
    }

    .public-mobile-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
    }

    .public-mobile-actions a {
        justify-content: center;
        padding: 11px 16px;
    }

    @media (max-width: 1080px) {
        .public-nav-links,
        .public-nav-actions {
            display: none;
        }

        .public-nav-toggle {
            display: inline-flex;
        }

        .public-nav-school-name {
            max-width: 240px;
        }
    }

    @media (max-width: 420px) {
        .public-mobile-links {
            grid-template-columns: 1fr;
        }

        .public-nav-school-name {
            max-width: 185px;
            font-size: 0.94rem;
        }
    }
</style>

<header class="public-navbar" id="publicNavbar">
    <div class="public-navbar-accent"></div>

    <nav class="public-nav-container" aria-label="Navigasi Utama">
        {{-- Kiri: Logo & Nama Sekolah --}}
        <a href="{{ route('public.home') }}" class="public-nav-brand">
            <div class="public-nav-logo-wrap">
                @if ($logoPath)
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $schoolName }}">
                @else
                    <div class="public-nav-logo-fallback">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="public-nav-title-group">
                <span class="public-nav-school-name" title="{{ $schoolName }}">{{ $schoolName }}</span>
                <span class="public-nav-school-sub">Portal Resmi Sekolah</span>
            </div>
        </a>

        {{-- Tengah: Menu Navigasi Desktop --}}
        <ul class="public-nav-links">
            <li><a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'is-active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('public.news') }}" class="{{ request()->routeIs('public.news*') ? 'is-active' : '' }}">Berita</a></li>
            <li><a href="{{ route('public.gallery') }}" class="{{ request()->routeIs('public.gallery*') ? 'is-active' : '' }}">Galeri</a></li>
            <li><a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about*') ? 'is-active' : '' }}">Tentang</a></li>
            <li><a href="{{ route('public.teachers') }}" class="{{ request()->routeIs('public.teachers*') ? 'is-active' : '' }}">Guru</a></li>
            <li><a href="{{ route('public.classes') }}" class="{{ request()->routeIs('public.classes*') ? 'is-active' : '' }}">Kelas</a></li>
            <!-- <li><a href="{{ route('public.subjects') }}" class="{{ request()->routeIs('public.subjects*') ? 'is-active' : '' }}">Mapel</a></li> -->
        </ul>

        {{-- Kanan: Tombol Hubungi & Login Admin --}}
        <div class="public-nav-actions">
            <a href="mailto:{{ $email }}" class="btn-nav-contact">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="3"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                <span>Hubungi</span>
            </a>

            <a href="{{ url('/admin') }}" class="btn-nav-admin">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <span>Login Admin</span>
            </a>
        </div>

        {{-- Tombol Hamburger Mobile --}}
        <button type="button" class="public-nav-toggle" id="publicNavToggle" aria-label="Buka Menu" aria-expanded="false">
            <svg id="navHamburgerIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </nav>

    {{-- Drawer Menu Mobile --}}
    <div class="public-mobile-drawer" id="publicMobileDrawer">
        <ul class="public-mobile-links">
            <li><a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'is-active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('public.news') }}" class="{{ request()->routeIs('public.news*') ? 'is-active' : '' }}">Berita</a></li>
            <li><a href="{{ route('public.gallery') }}" class="{{ request()->routeIs('public.gallery*') ? 'is-active' : '' }}">Galeri</a></li>
            <li><a href="{{ route('public.about') }}" class="{{ request()->routeIs('public.about*') ? 'is-active' : '' }}">Tentang</a></li>
            <li><a href="{{ route('public.teachers') }}" class="{{ request()->routeIs('public.teachers*') ? 'is-active' : '' }}">Guru</a></li>
            <li><a href="{{ route('public.classes') }}" class="{{ request()->routeIs('public.classes*') ? 'is-active' : '' }}">Kelas</a></li>
            <!-- <li><a href="{{ route('public.subjects') }}" class="{{ request()->routeIs('public.subjects*') ? 'is-active' : '' }}">Mapel</a></li> -->
        </ul>

        <div class="public-mobile-actions">
            <a href="mailto:{{ $email }}" class="btn-nav-contact">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="3"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                <span>Hubungi Sekolah</span>
            </a>

            <a href="{{ url('/admin') }}" class="btn-nav-admin">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <span>Login Admin</span>
            </a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.getElementById('publicNavbar');
        const toggleBtn = document.getElementById('publicNavToggle');
        const mobileDrawer = document.getElementById('publicMobileDrawer');
        const hamburgerIcon = document.getElementById('navHamburgerIcon');

        if (navbar) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 12) {
                    navbar.classList.add('is-scrolled');
                } else {
                    navbar.classList.remove('is-scrolled');
                }
            }, { passive: true });
        }

        if (toggleBtn && mobileDrawer) {
            toggleBtn.addEventListener('click', function () {
                const isOpen = mobileDrawer.classList.toggle('is-open');
                toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                hamburgerIcon.innerHTML = isOpen
                    ? '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'
                    : '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>';
            });
        }
    });
</script>