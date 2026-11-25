@extends('layouts.public')

@section('title', 'Berita & Artikel | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('content')
<div class="portal-page-wrap">
    {{-- Header / Hero Berita --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div>
                <span class="portal-badge-top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                        <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                    </svg>
                    Informasi & Publikasi
                </span>
                <h1>Berita & Artikel</h1>
                <p class="portal-hero-sub">Kabar terbaru, pengumuman kegiatan, serta prestasi warga {{ $profile->school_name ?? 'sekolah' }}.</p>
            </div>

            <form method="GET" action="{{ route('public.news') }}" class="news-search-form">
                <div class="news-search-box">
                    <span class="news-search-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input
                        name="q"
                        type="search"
                        placeholder="Cari judul atau topik berita..."
                        value="{{ request('q') }}"
                        class="news-search-input"
                        aria-label="Cari berita"
                    >
                    @if (filled(request('q')))
                        <a href="{{ route('public.news') }}" class="news-search-clear" title="Hapus pencarian">&times;</a>
                    @endif
                    <button type="submit" class="news-search-btn">Cari</button>
                </div>
            </form>
        </div>
    </header>

    {{-- Konten Utama --}}
    <section class="portal-container portal-section">
        @if (filled(request('q')))
            <div class="news-filter-bar">
                <span>
                    Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                    ({{ $posts instanceof \Illuminate\Pagination\AbstractPaginator ? $posts->total() : $posts->count() }} artikel)
                </span>
                <a href="{{ route('public.news') }}" class="news-reset-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                    </svg>
                    Tampilkan Semua Berita
                </a>
            </div>
        @endif

        <div class="portal-grid">
            @forelse ($posts as $post)
                <article class="portal-card">
                    <a href="{{ route('public.news.show', $post->slug) }}" class="portal-media-thumb">
                        @if ($post->category)
                            <span class="home-media-badge">{{ $post->category->name }}</span>
                        @endif

                        @if (!empty($post->thumbnail))
                            <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" loading="lazy">
                        @else
                            <div class="portal-media-fallback">
                                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                    <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                                </svg>
                                <span>BERITA SEKOLAH</span>
                            </div>
                        @endif
                    </a>

                    <div class="portal-card-body">
                        <h2 class="home-media-title">
                            <a href="{{ route('public.news.show', $post->slug) }}">{{ $post->title }}</a>
                        </h2>

                        <p class="home-media-excerpt">
                            {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 130) }}
                        </p>

                        <a href="{{ route('public.news.show', $post->slug) }}" class="portal-link-arrow" style="margin-top: auto;">
                            <span>Baca Selengkapnya</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <footer class="portal-card-footer">
                        <div class="news-meta-group">
                            <span class="news-meta-item" title="Penulis">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span class="news-meta-author">{{ $post->author->name ?? 'Admin' }}</span>
                            </span>

                            <span class="news-meta-item" title="Tanggal Terbit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('d M Y') : '-' }}</span>
                            </span>
                        </div>

                        <span class="news-meta-item" title="Jumlah Dibaca">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>{{ number_format($post->views ?? 0) }}</span>
                        </span>
                    </footer>
                </article>
            @empty
                <div class="portal-empty-state">
                    <div class="portal-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                            <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                        </svg>
                    </div>
                    <h3>{{ filled(request('q')) ? 'Berita Tidak Ditemukan' : 'Belum Ada Berita' }}</h3>
                    <p style="{{ filled(request('q')) ? 'margin-bottom: 20px;' : '' }}">
                        {{ filled(request('q'))
                            ? 'Tidak ada artikel yang cocok dengan kata kunci "' . request('q') . '". Coba gunakan kata kunci lain.'
                            : 'Saat ini belum ada berita atau artikel yang dipublikasikan.' }}
                    </p>
                    @if (filled(request('q')))
                        <a href="{{ route('public.news') }}" class="portal-btn-primary">Lihat Semua Berita</a>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($posts instanceof \Illuminate\Pagination\AbstractPaginator && $posts->hasPages())
            <div class="portal-pagination">
                {{ $posts->withQueryString()->links() }}
            </div>
        @endif
    </section>
</div>
@endsection