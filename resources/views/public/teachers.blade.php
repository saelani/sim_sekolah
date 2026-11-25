@extends('layouts.public')

@section('title', 'Guru & Pengajar | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .teachers-page-wrap {
        min-height: calc(100vh - 140px);
        background-color: #f8f7f4;
        color: #1e293b;
        padding-bottom: 72px;
    }

    /* ================= HERO SECTION ================= */
    .teachers-hero {
        background: linear-gradient(135deg, #132c47 0%, #1a3a5c 55%, #2c5f8a 100%);
        color: #ffffff;
        padding: 52px 20px 64px;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid #c59b27;
    }

    .teachers-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(at 85% 20%, rgba(197, 155, 39, 0.14) 0px, transparent 50%),
            radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 100% 100%, 24px 24px;
        pointer-events: none;
    }

    .teachers-hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
    }

    .teachers-badge-top {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #f8e5a8;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .teachers-hero h1 {
        font-size: clamp(1.8rem, 3.2vw, 2.45rem);
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 8px;
        line-height: 1.2;
        letter-spacing: -0.015em;
    }

    .teachers-hero-sub {
        font-size: 0.98rem;
        color: rgba(255, 255, 255, 0.86);
        margin: 0;
        max-width: 560px;
        line-height: 1.55;
    }

    .teachers-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 11px 18px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 0.88rem;
    }

    .teachers-stat-pill strong {
        font-size: 1.15rem;
        color: #f8e5a8;
    }

    /* ================= MAIN CONTAINER & FILTER BAR ================= */
    .teachers-main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 20px 0;
    }

    .teachers-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 14px;
        padding: 12px 16px;
        margin-bottom: 26px;
        box-shadow: 0 4px 14px rgba(26, 58, 92, 0.04);
    }

    .teachers-search-box {
        position: relative;
        flex: 1;
        min-width: 240px;
        max-width: 420px;
        display: flex;
        align-items: center;
    }

    .teachers-search-icon {
        position: absolute;
        left: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        pointer-events: none;
    }

    .teachers-search-input {
        width: 100%;
        padding: 9px 36px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        background: #f8fafc;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .teachers-search-input:focus {
        outline: none;
        background: #ffffff;
        border-color: #2c5f8a;
        box-shadow: 0 0 0 3px rgba(44, 95, 138, 0.12);
    }

    .teachers-clear-btn {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 6px;
        display: none;
    }

    .teachers-clear-btn:hover {
        color: #dc2626;
        background: #fef2f2;
    }

    .teachers-count-info {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .teachers-count-info strong {
        color: #1a3a5c;
    }

    /* ================= TEACHERS GRID ================= */
    .teachers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 22px;
    }

    .teacher-card {
        background: #ffffff;
        border: 1px solid #e6e2d8;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-align: center;
        position: relative;
        box-shadow: 0 4px 16px rgba(26, 58, 92, 0.04);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .teacher-card:hover {
        transform: translateY(-5px);
        border-color: #cbd5e1;
        box-shadow: 0 16px 34px rgba(26, 58, 92, 0.11);
    }

    .teacher-card-banner {
        height: 64px;
        background: linear-gradient(135deg, #1a3a5c 0%, #2c5f8a 100%);
        position: relative;
    }

    .teacher-card-banner::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, #1a3a5c, #c59b27, #1a3a5c);
    }

    .teacher-avatar-wrap {
        width: 88px;
        height: 88px;
        margin: -44px auto 14px;
        padding: 3px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c59b27 0%, #f3dc93 50%, #2c5f8a 100%);
        box-shadow: 0 6px 18px rgba(26, 58, 92, 0.16);
        position: relative;
        z-index: 2;
    }

    .teacher-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
        display: block;
    }

    .teacher-avatar-fallback {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3a5c 0%, #265380 100%);
        color: #ffffff;
        font-size: 1.45rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 0.03em;
    }

    .teacher-card-body {
        padding: 0 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .teacher-name {
        font-size: 1.08rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px;
        line-height: 1.35;
    }

    .teacher-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(197, 155, 39, 0.14);
        color: #967010;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        margin-bottom: 8px;
    }

    .teacher-id-meta {
        font-size: 0.77rem;
        color: #64748b;
        font-family: monospace;
        margin-bottom: 4px;
    }

    /* Mata Pelajaran */
    .teacher-subjects-box {
        width: 100%;
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid #f1efe9;
    }

    .teacher-subjects-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .teacher-subject-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
    }

    .teacher-subject-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #1a3a5c;
        font-size: 0.77rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .teacher-no-subject {
        font-size: 0.8rem;
        color: #94a3b8;
        font-style: italic;
    }

    /* Empty State */
    .teachers-empty-state {
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        padding: 56px 24px;
        text-align: center;
    }

    .teachers-empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: rgba(26, 58, 92, 0.07);
        color: #1a3a5c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .teachers-empty-state h3 {
        margin: 0 0 6px;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
    }

    .teachers-empty-state p {
        margin: 0;
        font-size: 0.9rem;
        color: #64748b;
    }

    .teachers-pagination {
        margin-top: 36px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 640px) {
        .teachers-hero {
            padding: 38px 16px 46px;
        }

        .teachers-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="teachers-page-wrap">
    {{-- ================= HERO BANNER ================= --}}
    <header class="teachers-hero">
        <div class="teachers-hero-inner">
            <div>
                <span class="teachers-badge-top">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Sumber Daya Manusia
                </span>
                <h1>Guru & Pengajar</h1>
                <p class="teachers-hero-sub">Para pendidik dan tenaga kependidikan {{ $profile->school_name ?? 'sekolah' }} yang berdedikasi dalam membimbing peserta didik.</p>
            </div>

            <div class="teachers-stat-pill">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8e5a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Total <strong>{{ $teachers instanceof \Illuminate\Pagination\AbstractPaginator ? $teachers->total() : $teachers->count() }}</strong> Pendidik</span>
            </div>
        </div>
    </header>

    {{-- ================= KONTEN UTAMA ================= --}}
    <section class="teachers-main-container">
        @if ($teachers->isNotEmpty())
            {{-- Toolbar Pencarian Instan --}}
            <div class="teachers-toolbar">
                <div class="teachers-search-box">
                    <span class="teachers-search-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="teacherFilterInput"
                        class="teachers-search-input"
                        placeholder="Cari nama guru, jabatan, atau mata pelajaran..."
                        aria-label="Cari guru"
                    >
                    <button type="button" id="teacherFilterClear" class="teachers-clear-btn" title="Hapus pencarian">&times;</button>
                </div>

                <div class="teachers-count-info">
                    Menampilkan <strong id="teacherVisibleCount">{{ $teachers->count() }}</strong> dari <strong>{{ $teachers->count() }}</strong> data
                </div>
            </div>

            {{-- Grid Kartu Guru --}}
            <div class="teachers-grid" id="teachersGrid">
                @foreach ($teachers as $teacher)
                    @php
                        $fullName = trim(
                            ($teacher->front_title ? $teacher->front_title . ' ' : '') .
                            $teacher->name .
                            ($teacher->back_title ? ', ' . $teacher->back_title : '')
                        );

                        $initials = collect(explode(' ', trim($teacher->name ?? 'G')))
                            ->filter()
                            ->take(2)
                            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                            ->join('');

                        $subjects = $teacher->teacherSubjectClass
                            ->pluck('subject.name')
                            ->filter()
                            ->unique()
                            ->values();

                        $idMeta = array_filter([
                            !empty($teacher->nip) ? 'NIP. ' . $teacher->nip : null,
                            !empty($teacher->nuptk) ? 'NUPTK. ' . $teacher->nuptk : null,
                        ]);

                        $searchIndex = mb_strtolower($fullName . ' ' . ($teacher->role_type ?? '') . ' ' . $subjects->implode(' '));
                    @endphp

                    <article class="teacher-card" data-search="{{ $searchIndex }}">
                        <div class="teacher-card-banner"></div>

                        <div class="teacher-avatar-wrap">
                            @if ($teacher->photo_path)
                                <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="{{ $teacher->name }}" class="teacher-avatar-img" loading="lazy">
                            @else
                                <div class="teacher-avatar-fallback">
                                    {{ $initials ?: 'G' }}
                                </div>
                            @endif
                        </div>

                        <div class="teacher-card-body">
                            <h2 class="teacher-name">{{ $fullName }}</h2>

                            <span class="teacher-role-badge">
                                {{ ucfirst($teacher->role_type ?? 'Tenaga Pendidik') }}
                            </span>

                            @if (!empty($idMeta))
                                <div class="teacher-id-meta">{{ implode(' · ', $idMeta) }}</div>
                            @endif

                            <div class="teacher-subjects-box">
                                <div class="teacher-subjects-label">Mata Pelajaran Diampu</div>

                                @if ($subjects->isNotEmpty())
                                    <div class="teacher-subject-pills">
                                        @foreach ($subjects as $subjectName)
                                            <span class="teacher-subject-pill">{{ $subjectName }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="teacher-no-subject">Tugas Tambahan / Non-Mapel</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- State Kosong Hasil Filter --}}
            <div class="teachers-empty-state" id="teacherNoMatchState" style="display: none;">
                <div class="teachers-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <h3>Guru Tidak Ditemukan</h3>
                <p>Tidak ada data guru atau mata pelajaran yang cocok dengan kata kunci pencarian Anda.</p>
            </div>

            {{-- Navigasi Halaman (Jika Menggunakan Pagination) --}}
            @if ($teachers instanceof \Illuminate\Pagination\AbstractPaginator && $teachers->hasPages())
                <div class="teachers-pagination">
                    {{ $teachers->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="teachers-empty-state">
                <div class="teachers-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>
                <h3>Belum Ada Data Guru</h3>
                <p>Saat ini belum ada data pendidik atau tenaga kependidikan yang terdaftar di sistem.</p>
            </div>
        @endif
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('teacherFilterInput');
        const clearBtn = document.getElementById('teacherFilterClear');
        const cards = document.querySelectorAll('#teachersGrid .teacher-card');
        const visibleCountEl = document.getElementById('teacherVisibleCount');
        const noMatchEl = document.getElementById('teacherNoMatchState');
        const gridEl = document.getElementById('teachersGrid');

        if (!searchInput || !cards.length) return;

        function filterTeachers() {
            const query = searchInput.value.trim().toLowerCase();
            let visible = 0;

            clearBtn.style.display = query.length > 0 ? 'block' : 'none';

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

        searchInput.addEventListener('input', filterTeachers);

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterTeachers();
            searchInput.focus();
        });
    });
</script>
@endsection