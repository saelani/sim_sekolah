@extends('layouts.public')

@section('title', 'Kelas & Watason | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .class-card-item {
        border-left: 4px solid var(--portal-navy);
    }

    .class-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .class-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--portal-navy);
        margin: 0;
        line-height: 1.3;
    }

    .class-meta-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: auto;
    }

    .class-meta-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 8px;
        background: #f1f5f9;
        color: var(--portal-navy);
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .class-meta-pill.gold {
        background: rgba(197, 155, 39, 0.15);
        color: #967010;
    }

    .class-teacher-info {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--portal-text);
        font-weight: 600;
        font-size: 0.82rem;
    }

    .class-clear-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 2px 8px;
        border-radius: 6px;
        display: none;
    }

    .class-clear-btn:hover {
        color: #dc2626;
        background: #fef2f2;
    }
</style>
@endsection

@section('content')
<div class="portal-page-wrap">
    {{-- ================= HERO BANNER ================= --}}
    <header class="portal-hero">
        <div class="portal-hero-inner">
            <div>
                <span class="portal-badge-top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Akademik & Rombongan Belajar
                </span>
                <h1>Kelas & Watason</h1>
                <p class="portal-hero-sub">Daftar kelas, fase pembelajaran, dan wali kelas di {{ $profile->school_name ?? 'sekolah' }}.</p>
            </div>

            <div class="portal-stat-pill">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8e5a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span>Total <strong>{{ $classes instanceof \Illuminate\Pagination\AbstractPaginator ? $classes->total() : $classes->count() }}</strong> Kelas</span>
            </div>
        </div>
    </header>

    {{-- ================= KONTEN UTAMA ================= --}}
    <section class="portal-container portal-section">
        @if ($classes->isNotEmpty())
            {{-- Toolbar Pencarian Instan --}}
            <div class="portal-toolbar">
                <div class="portal-search-box" style="flex: 1; max-width: 420px; min-width: 240px;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="text"
                        id="classFilterInput"
                        class="portal-search-input"
                        placeholder="Cari nama kelas, wali kelas, fase, atau tingkat..."
                        aria-label="Cari kelas"
                    >
                    <button type="button" id="classFilterClear" class="class-clear-btn" title="Hapus pencarian">&times;</button>
                </div>

                <div style="font-size: 0.85rem; color: var(--portal-muted); font-weight: 500;">
                    Menampilkan <strong id="classVisibleCount" style="color: var(--portal-navy);">{{ $classes->count() }}</strong> dari <strong style="color: var(--portal-navy);">{{ $classes->count() }}</strong> kelas
                </div>
            </div>

            {{-- Grid Daftar Kelas --}}
            <div class="portal-grid portal-grid--compact" id="classesGrid">
                @foreach ($classes as $class)
                    @php
                        $yearText = strtoupper($class->academicYear->year ?? '-');
                        $phaseText = strtoupper($class->phase ?? '-');
                        $levelText = strtoupper($class->level ?? '-');
                        $teacherName = $class->homeroomTeacher->name ?? '-';
                        $searchIndex = mb_strtolower($class->name . ' ' . $yearText . ' ' . $phaseText . ' ' . $levelText . ' ' . $teacherName);
                    @endphp

                    <article class="portal-card class-card-item" data-search="{{ $searchIndex }}">
                        <div class="portal-card-body">
                            <div class="class-card-top">
                                <h2 class="class-card-title">{{ $class->name }}</h2>

                                @if (isset($class->students_count))
                                    <span class="home-class-badge">{{ $class->students_count }} Siswa</span>
                                @endif
                            </div>

                            <div class="class-meta-pills">
                                <span class="class-meta-pill gold">TA {{ $yearText }}</span>
                                <span class="class-meta-pill">Fase {{ $phaseText }}</span>
                                <span class="class-meta-pill">Tingkat {{ $levelText }}</span>
                            </div>
                        </div>

                        <footer class="portal-card-footer">
                            <span style="font-size: 0.75rem; font-weight: 600;">Wali Kelas</span>
                            <span class="class-teacher-info">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--portal-navy)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span>{{ $teacherName }}</span>
                            </span>
                        </footer>
                    </article>
                @endforeach
            </div>

            {{-- State Kosong Hasil Filter --}}
            <div class="portal-empty-state" id="classNoMatchState" style="display: none;">
                <div class="portal-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <h3>Kelas Tidak Ditemukan</h3>
                <p>Tidak ada data kelas atau wali kelas yang cocok dengan kata kunci pencarian Anda.</p>
            </div>

            {{-- Navigasi Halaman (Jika Menggunakan Pagination) --}}
            @if ($classes instanceof \Illuminate\Pagination\AbstractPaginator && $classes->hasPages())
                <div class="portal-pagination">
                    {{ $classes->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="portal-empty-state">
                <div class="portal-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h3>Belum Ada Data Kelas</h3>
                <p>Tidak ada kelas yang terdaftar di sistem saat ini.</p>
            </div>
        @endif
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('classFilterInput');
        const clearBtn = document.getElementById('classFilterClear');
        const cards = document.querySelectorAll('#classesGrid .class-card-item');
        const visibleCountEl = document.getElementById('classVisibleCount');
        const noMatchEl = document.getElementById('classNoMatchState');
        const gridEl = document.getElementById('classesGrid');

        if (!searchInput || !cards.length) return;

        function filterClasses() {
            const query = searchInput.value.trim().toLowerCase();
            let visible = 0;

            clearBtn.style.display = query.length > 0 ? 'inline-block' : 'none';

            cards.forEach(function (card) {
                const haystack = card.getAttribute('data-search') || '';
                const matched = haystack.includes(query);
                card.style.display = matched ? 'flex' : 'none';
                if (matched) visible++;
            });

            if (visibleCountEl) {
                visibleCountEl.textContent = visible;
            }

            if (noMatchEl && gridEl) {
                noMatchEl.style.display = visible === 0 ? 'block' : 'none';
                gridEl.style.display = visible === 0 ? 'none' : 'grid';
            }
        }

        searchInput.addEventListener('input', filterClasses);

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterClasses();
            searchInput.focus();
        });
    });
</script>
@endsection