@extends('layouts.public')

@section('title', ($post->title ?? 'Berita') . ' | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .article-hero-wrap {
        max-width: 860px;
        margin: 0 auto;
        width: 100%;
    }

    .article-nav-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .article-back-btn {
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
        transition: background-color 0.18s ease, transform 0.18s ease;
    }

    .article-back-btn:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateX(-2px);
    }

    .article-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.72);
    }

    .article-breadcrumb a {
        color: var(--portal-gold-soft);
        font-weight: 600;
        text-decoration: none;
    }

    .article-breadcrumb a:hover {
        text-decoration: underline;
    }

    .article-hero-title {
        font-size: clamp(1.65rem, 3vw, 2.35rem) !important;
        line-height: 1.28 !important;
        margin-bottom: 18px !important;
    }

    .article-meta-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px 18px;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.16);
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.88);
    }

    .article-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .article-meta-item svg {
        color: var(--portal-gold-soft);
        flex-shrink: 0;
    }

    /* Container Baca Artikel */
    .article-container {
        max-width: 860px;
        margin: 0 auto;
    }

    .article-card-body {
        padding: 32px 36px;
    }

    .article-featured-img {
        width: 100%;
        max-height: 440px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 28px;
        border: 1px solid var(--portal-border);
        background: #f1f5f9;
    }

    .article-content {
        font-size: 1.04rem;
        line-height: 1.88;
        color: #334155;
        word-break: break-word;
    }

    .article-content p {
        margin-bottom: 16px;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 18px 0;
    }

    .article-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 999px;
        background: rgba(26, 58, 92, 0.08);
        color: var(--portal-navy);
        font-size: 0.8rem;
        font-weight: 700;
    }

    .article-share-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 13px;
        border-radius: 9px;
        border: 1px solid #dcd7cc;
        background: #ffffff;
        color: var(--portal-navy);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.16s ease;
    }

    .article-share-btn:hover {
        background: var(--portal-navy);
        border-color: var(--portal-navy);
        color: #ffffff;
    }

    @media (max-width: 640px) {
        .article-card-body {
            padding: 22px 20px;
        }

        .article-featured-img {
            max-height: 260px;
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
@php
    $categoryName = $post->category->name ?? $post->PostCategory->name ?? null;
@endphp

<div class="portal-page-wrap">
    {{-- ================= HERO ARTIKEL ================= --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div class="article-hero-wrap">
                <div class="article-nav-row">
                    <a href="{{ route('public.news') }}" class="article-back-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                        </svg>
                        <span>Kembali ke Berita</span>
                    </a>

                    <nav class="article-breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('public.home') }}">Beranda</a>
                        <span>/</span>
                        <a href="{{ route('public.news') }}">Berita</a>
                        @if ($categoryName)
                            <span>/</span>
                            <span>{{ $categoryName }}</span>
                        @endif
                    </nav>
                </div>

                <div>
                    <span class="portal-badge-top">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                            <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                        </svg>
                        {{ $categoryName ?? 'Berita Sekolah' }}
                    </span>
                </div>

                <h1 class="article-hero-title">{{ $post->title }}</h1>

                <div class="article-meta-bar">
                    <span class="article-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Oleh <strong>{{ $post->author->name ?? 'Admin' }}</strong></span>
                    </span>

                    <span class="article-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') : '-' }}</span>
                    </span>

                    <span class="article-meta-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>{{ number_format($post->views ?? 0) }} kali dibaca</span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    {{-- ================= KONTEN BERITA ================= --}}
    <section class="portal-container portal-section">
        <div class="article-container">
            <article class="portal-card" style="transform: none;">
                <div class="article-card-body">
                    @if (!empty($post->thumbnail))
                        <img
                            src="{{ $post->thumbnail }}"
                            alt="{{ $post->title }}"
                            class="article-featured-img"
                        >
                    @endif

                    <div class="article-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <footer class="portal-card-footer" style="padding: 16px 36px;">
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                        @if ($categoryName)
                            <span class="article-tag-pill">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                                </svg>
                                <span>{{ $categoryName }}</span>
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button type="button" class="article-share-btn" id="copyArticleLinkBtn" data-url="{{ url()->current() }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                            </svg>
                            <span id="copyArticleLinkLabel">Salin Tautan</span>
                        </button>

                        <a href="{{ route('public.news') }}" class="portal-link-arrow">
                            <span>Berita Lainnya</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>
                </footer>
            </article>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyBtn = document.getElementById('copyArticleLinkBtn');
        const copyLabel = document.getElementById('copyArticleLinkLabel');

        if (!copyBtn || !copyLabel) return;

        copyBtn.addEventListener('click', function () {
            const url = copyBtn.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function () {
                    copyLabel.textContent = 'Tautan Tersalin!';
                    setTimeout(function () {
                        copyLabel.textContent = 'Salin Tautan';
                    }, 2200);
                });
            }
        });
    });
</script>
@endsection