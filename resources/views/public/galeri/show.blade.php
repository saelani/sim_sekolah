@extends('layouts.public')

@section('title', ($gallery->title ?? 'Galeri') . ' | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .gallery-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 16px;
        transition: background-color 0.18s ease, transform 0.18s ease;
    }

    .gallery-back-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateX(-2px);
    }

    .gallery-detail-thumb {
        position: relative;
        display: block;
        height: 235px;
        overflow: hidden;
        background: #e2e8f0;
        cursor: pointer;
    }

    .gallery-detail-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }

    .portal-card:hover .gallery-detail-thumb img {
        transform: scale(1.06);
    }

    .gallery-zoom-hint {
        position: absolute;
        inset: 0;
        background: rgba(19, 44, 71, 0.42);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.22s ease;
    }

    .gallery-zoom-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(26, 58, 92, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .portal-card:hover .gallery-zoom-hint {
        opacity: 1;
    }

    .gallery-file-box {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
        color: var(--portal-navy);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 20px;
        text-align: center;
        transition: background 0.2s ease;
    }

    .portal-card:hover .gallery-file-box {
        background: linear-gradient(135deg, #eef2f6 0%, #e2e8f0 100%);
    }

    .gallery-file-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(26, 58, 92, 0.09);
        color: var(--portal-navy);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-file-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--portal-navy);
    }

    .gallery-file-sub {
        font-size: 0.76rem;
        color: var(--portal-muted);
    }

    .gallery-item-caption {
        padding: 15px 18px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--portal-text);
        line-height: 1.45;
        border-top: 1px solid #f1efe9;
    }

    /* Lightbox Modal */
    .gallery-lightbox {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(10, 22, 38, 0.92);
        backdrop-filter: blur(6px);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .gallery-lightbox.is-open {
        display: flex;
    }

    .gallery-lightbox-close {
        position: absolute;
        top: 20px;
        right: 24px;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.18s ease;
    }

    .gallery-lightbox-close:hover {
        background: rgba(220, 38, 38, 0.85);
    }

    .gallery-lightbox-img {
        max-width: 92vw;
        max-height: 78vh;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        object-fit: contain;
    }

    .gallery-lightbox-bar {
        margin-top: 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 14px;
        color: #ffffff;
        font-size: 0.92rem;
        text-align: center;
    }

    .gallery-lightbox-raw {
        color: var(--portal-gold-soft);
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
@php
    $totalItems = $items instanceof \Illuminate\Pagination\AbstractPaginator ? $items->total() : $items->count();
@endphp

<div class="portal-page-wrap">
    {{-- ================= HERO ALBUM ================= --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div>
                <a href="{{ route('public.gallery') }}" class="gallery-back-btn">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    <span>Kembali ke Galeri</span>
                </a>

                <div>
                    <span class="portal-badge-top">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        Album Dokumentasi
                    </span>
                </div>

                <h1>{{ $gallery->title }}</h1>

                @if (!empty($gallery->description))
                    <p class="portal-hero-sub">{{ $gallery->description }}</p>
                @endif
            </div>

            <div class="portal-stat-pill">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8e5a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <span>Total <strong>{{ $totalItems }}</strong> Dokumentasi</span>
            </div>
        </div>
    </header>

    {{-- ================= DAFTAR ITEM GALERI ================= --}}
    <section class="portal-container portal-section">
        <div class="portal-grid portal-grid--compact">
            @forelse ($items as $item)
                <article class="portal-card">
                    @if ($item->type === 'image' && !empty($item->file_or_url))
                        <a
                            href="{{ $item->file_or_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="gallery-detail-thumb js-lightbox-trigger"
                            data-src="{{ $item->file_or_url }}"
                            data-caption="{{ $item->caption ?? $gallery->title }}"
                        >
                            <img src="{{ $item->file_or_url }}" alt="{{ $item->caption ?? $gallery->title }}" loading="lazy">
                            <div class="gallery-zoom-hint">
                                <span class="gallery-zoom-badge">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
                                    </svg>
                                    <span>Perbesar Foto</span>
                                </span>
                            </div>
                        </a>
                    @else
                        <a href="{{ $item->file_or_url }}" target="_blank" rel="noopener noreferrer" class="gallery-detail-thumb" style="text-decoration: none;">
                            <div class="gallery-file-box">
                                <div class="gallery-file-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                </div>
                                <span class="gallery-file-label">Tautan Video / File</span>
                                <span class="gallery-file-sub">Klik untuk membuka di tab baru</span>
                            </div>
                        </a>
                    @endif

                    @if (!empty($item->caption))
                        <div class="gallery-item-caption">
                            {{ $item->caption }}
                        </div>
                    @endif
                </article>
            @empty
                <div class="portal-empty-state">
                    <div class="portal-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                    <h3>Album Masih Kosong</h3>
                    <p style="margin-bottom: 20px;">Belum ada foto atau dokumentasi yang ditambahkan ke dalam galeri ini.</p>
                    <a href="{{ route('public.gallery') }}" class="portal-btn-primary">Kembali ke Daftar Galeri</a>
                </div>
            @endforelse
        </div>

        @if ($items instanceof \Illuminate\Pagination\AbstractPaginator && $items->hasPages())
            <div class="portal-pagination">
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </section>
</div>

{{-- Lightbox Preview Foto --}}
<div class="gallery-lightbox" id="galleryLightbox" role="dialog" aria-modal="true" aria-label="Pratinjau Foto">
    <button type="button" class="gallery-lightbox-close" id="lightboxClose" title="Tutup (Esc)">&times;</button>
    <img src="" alt="" class="gallery-lightbox-img" id="lightboxImg">
    <div class="gallery-lightbox-bar">
        <span id="lightboxCaption"></span>
        <a href="#" target="_blank" rel="noopener noreferrer" class="gallery-lightbox-raw" id="lightboxRawLink">Buka Ukuran Asli &nearr;</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lightbox = document.getElementById('galleryLightbox');
        const lightboxImg = document.getElementById('lightboxImg');
        const lightboxCaption = document.getElementById('lightboxCaption');
        const lightboxRawLink = document.getElementById('lightboxRawLink');
        const closeBtn = document.getElementById('lightboxClose');
        const triggers = document.querySelectorAll('.js-lightbox-trigger');

        if (!lightbox || !triggers.length) return;

        function closeLightbox() {
            lightbox.classList.remove('is-open');
            lightboxImg.src = '';
            document.body.style.overflow = '';
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                const src = trigger.getAttribute('data-src');
                const caption = trigger.getAttribute('data-caption') || '';

                lightboxImg.src = src;
                lightboxImg.alt = caption;
                lightboxCaption.textContent = caption;
                lightboxRawLink.href = src;
                lightbox.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtn.addEventListener('click', closeLightbox);

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && lightbox.classList.contains('is-open')) {
                closeLightbox();
            }
        });
    });
</script>
@endsection