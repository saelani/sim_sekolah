@extends('layouts.public')

@section('title', 'Galeri | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('content')
<div class="portal-page-wrap">
    {{-- Header / Hero Galeri --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div>
                <span class="portal-badge-top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    Dokumentasi Kegiatan
                </span>
                <h1>Galeri Sekolah</h1>
                <p class="portal-hero-sub">Abadikan berbagai momen berharga, kegiatan belajar, dan prestasi di {{ $profile->school_name ?? 'sekolah' }}.</p>
            </div>

            <div class="portal-stat-pill">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8e5a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
                <span>Total <strong>{{ $galleries instanceof \Illuminate\Pagination\AbstractPaginator ? $galleries->total() : $galleries->count() }}</strong> Album</span>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <section class="portal-container portal-section">
        <div class="portal-grid">
            @forelse ($galleries as $gallery)
                @php
                    $first = $gallery->GalleryItem->first();
                    $hasImage = $first && $first->type === 'image' && !empty($first->file_or_url);
                    $totalItems = $gallery->items_count ?? $gallery->GalleryItem->count();
                @endphp

                <article class="portal-card">
                    {{-- Sampul Album --}}
                    <a href="{{ route('public.gallery.show', $gallery->slug) }}" class="portal-media-thumb gallery-cover-overlay">
                        @if ($hasImage)
                            <img src="{{ $first->file_or_url }}" alt="{{ $gallery->title }}" loading="lazy">
                        @else
                            <div class="portal-media-fallback">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span>ALBUM GALERI</span>
                            </div>
                        @endif

                        <span class="gallery-count-badge">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            <span>{{ $totalItems }} Foto</span>
                        </span>
                    </a>

                    {{-- Detail Album --}}
                    <div class="portal-card-body">
                        <h2 class="home-media-title">
                            <a href="{{ route('public.gallery.show', $gallery->slug) }}">{{ $gallery->title }}</a>
                        </h2>

                        <p class="home-media-excerpt" style="margin-bottom: 0; -webkit-line-clamp: 2;">
                            {{ $gallery->description ?: 'Dokumentasi kegiatan ' . $gallery->title . '.' }}
                        </p>
                    </div>

                    {{-- Footer Kartu --}}
                    <footer class="portal-card-footer">
                        <span class="gallery-meta-count">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span>{{ $totalItems }} Dokumentasi</span>
                        </span>

                        <a href="{{ route('public.gallery.show', $gallery->slug) }}" class="portal-link-arrow">
                            <span>Buka Album</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </footer>
                </article>
            @empty
                <div class="portal-empty-state">
                    <div class="portal-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Galeri</h3>
                    <p>Saat ini belum ada album foto atau dokumentasi kegiatan yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        @if ($galleries instanceof \Illuminate\Pagination\AbstractPaginator && $galleries->hasPages())
            <div class="portal-pagination">
                {{ $galleries->withQueryString()->links() }}
            </div>
        @endif
    </section>
</div>
@endsection