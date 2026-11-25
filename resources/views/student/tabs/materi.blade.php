<div class="tab-pane" id="materi" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Materi & Modul</h2>
            <span class="dash-panel-meta">{{ $materials->count() }} modul tersedia</span>
        </div>

        <div class="dash-panel-body">
            @if ($materials->isEmpty())
                <div class="portal-empty-state" style="border: none; padding: 32px 16px;">
                    <h3>Belum Ada Materi</h3>
                    <p>Modul/citra pembelajaran akan muncul di sini saat guru mengunggah materi aktif.</p>
                </div>
            @else
                <div class="portal-grid">
                    @foreach ($materials as $mat)
                        @php
                            $subjectName = $mat->subject->name ?? '-';
                            $subjectCode = $mat->subject->code ?? '-';
                            $typeLabel = $mat->type ? ucfirst(str_replace('_', ' ', trim($mat->type))) : 'Modul';

                            // 1. Deteksi YouTube
                            $isYouTube = false;
                            $videoId = null;

                            if (!empty($mat->external_url)) {
                                $externalUrl = trim($mat->external_url);
                                $host = strtolower((string) parse_url($externalUrl, PHP_URL_HOST));
                                $path = (string) parse_url($externalUrl, PHP_URL_PATH);
                                $query = (string) parse_url($externalUrl, PHP_URL_QUERY);
                                $segments = array_values(array_filter(explode('/', trim($path, '/'))));

                                if (str_contains($host, 'youtu.be') && !empty($segments[0])) {
                                    $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $segments[0]);
                                    $isYouTube = $videoId !== '';
                                } elseif (str_contains($host, 'youtube.com') && str_contains($path, 'watch')) {
                                    parse_str($query, $queryParams);
                                    if (!empty($queryParams['v']) && is_string($queryParams['v'])) {
                                        $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $queryParams['v']);
                                        $isYouTube = $videoId !== '';
                                    }
                                } elseif (str_contains($host, 'youtube.com') && !empty($segments[1]) && in_array($segments[0], ['embed', 'shorts'], true)) {
                                    $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $segments[1]);
                                    $isYouTube = $videoId !== '';
                                }
                            }

                            // 2. Deteksi File (Gambar / PDF / Lainnya)
                            $fileUrl = null;
                            $fileExt = '';
                            $isImage = false;
                            $isPdf = false;

                            if (!empty($mat->file_path)) {
                                $cleanPath = ltrim($mat->file_path, '/');
                                $fileUrl = asset('storage/' . $cleanPath);
                                $fileExt = strtolower((string) pathinfo($cleanPath, PATHINFO_EXTENSION));
                                $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                                $isPdf = $fileExt === 'pdf';
                            }

                            $plainTextContent = trim((string) ($mat->content ?? ''));

                            // Tentukan mode modal & sumber konten
                            $modalKind = null;
                            $modalSrc = null;
                            $buttonText = null;

                            if ($isYouTube && $videoId) {
                                $modalKind = 'youtube';
                                $modalSrc = $videoId;
                                $buttonText = 'Tonton Video';
                            } elseif ($isPdf && $fileUrl) {
                                $modalKind = 'pdf';
                                $modalSrc = $fileUrl;
                                $buttonText = 'Baca PDF';
                            } elseif ($isImage && $fileUrl) {
                                $modalKind = 'image';
                                $modalSrc = $fileUrl;
                                $buttonText = 'Lihat Gambar';
                            } elseif ($plainTextContent !== '') {
                                $modalKind = 'text';
                                $modalSrc = $plainTextContent;
                                $buttonText = 'Baca Teks';
                            }
                        @endphp

                        <article class="portal-card dash-materi-card">
                            <div class="portal-card-body">
                                <div>
                                    <span class="dash-materi-subject">
                                        {{ $subjectCode }} · {{ $subjectName }}
                                    </span>
                                    @if ($mat->class_level)
                                        <span class="dash-materi-type">{{ $typeLabel }}</span>
                                    @endif
                                </div>

                                <h3 class="dash-materi-title">{{ $mat->title }}</h3>

                                <div class="dash-materi-meta">
                                    @if ($mat->class_level)
                                        <div>Level: <strong>{{ $mat->class_level }}</strong></div>
                                    @endif
                                    @if ($mat->external_url && !$isYouTube)
                                        <div style="font-size:0.82rem;color:#6b7280;">
                                            <a href="{{ $mat->external_url }}" target="_blank" rel="noopener noreferrer" style="color:#3b82f6;text-decoration:underline;">
                                                Buka Link Eksternal ›
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                @if ($plainTextContent !== '')
                                    <p style="margin-top: 8px; font-size: 0.86rem; color: #475569; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $plainTextContent }}
                                    </p>
                                @endif
                            </div>

                            <footer class="portal-card-footer">
                                @if ($modalKind)
                                    <button
                                        type="button"
                                        class="portal-btn-primary js-open-materi-modal"
                                        style="width: 100%;"
                                        data-kind="{{ $modalKind }}"
                                        data-title="{{ $mat->title }}"
                                        data-meta="{{ $subjectCode }} · {{ $subjectName }}"
                                        data-src="{{ $modalSrc }}"
                                        data-download-url="{{ $fileUrl ?? '' }}"
                                    >
                                        <span>{{ $buttonText }}</span>
                                    </button>
                                @elseif ($fileUrl)
                                    <a
                                        href="{{ $fileUrl }}"
                                        class="portal-btn-primary"
                                        style="width: 100%;"
                                        download
                                    >
                                        <span>Unduh ({{ strtoupper($fileExt ?: 'FILE') }})</span>
                                    </a>
                                @else
                                    <button
                                        type="button"
                                        class="portal-btn-primary"
                                        style="width: 100%; opacity: 0.55; cursor: not-allowed;"
                                        disabled
                                    >
                                        Belum Ada Isi
                                    </button>
                                @endif
                            </footer>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Dinamis Materi --}}
<div
    id="materiPreviewModal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="materiModalTitle"
    style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.72); padding: 20px; align-items: center; justify-content: center;"
>
    <div style="width: 100%; max-width: 900px; max-height: 90vh; background: #ffffff; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,0.35);">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #e2e8f0;">
            <div>
                <div id="materiModalMeta" style="font-size: 0.78rem; color: #64748b;"></div>
                <h3 id="materiModalTitle" style="margin: 2px 0 0; font-size: 1.05rem; color: #0f172a;"></h3>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a
                    id="materiModalDownload"
                    href="#"
                    download
                    style="display: none; padding: 6px 12px; font-size: 0.85rem; border-radius: 6px; background: #f1f5f9; color: #0f172a; text-decoration: none; border: 1px solid #cbd5e1;"
                >
                    Unduh
                </a>
                <button
                    type="button"
                    id="materiModalClose"
                    aria-label="Tutup modal"
                    style="width: 34px; height: 34px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #0f172a; font-size: 1.1rem; cursor: pointer;"
                >
                    ×
                </button>
            </div>
        </div>

        <div id="materiModalBody" style="padding: 18px; overflow-y: auto; flex: 1;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('materiPreviewModal');
    const modalTitle = document.getElementById('materiModalTitle');
    const modalMeta = document.getElementById('materiModalMeta');
    const modalBody = document.getElementById('materiModalBody');
    const modalDownload = document.getElementById('materiModalDownload');
    const closeBtn = document.getElementById('materiModalClose');

    function closeModal() {
        modal.style.display = 'none';
        // Hapus iframe/konten agar video YouTube langsung berhenti
        modalBody.innerHTML = '';
        modalDownload.style.display = 'none';
        modalDownload.setAttribute('href', '#');
    }

    function openModal(btn) {
        const kind = btn.getAttribute('data-kind');
        const title = btn.getAttribute('data-title') || 'Materi';
        const meta = btn.getAttribute('data-meta') || '';
        const src = btn.getAttribute('data-src') || '';
        const downloadUrl = btn.getAttribute('data-download-url') || '';

        modalTitle.textContent = title;
        modalMeta.innerHTML = meta;
        modalBody.innerHTML = '';

        if (downloadUrl) {
            modalDownload.href = downloadUrl;
            modalDownload.style.display = 'inline-block';
        } else {
            modalDownload.style.display = 'none';
        }

        if (kind === 'youtube') {
            const wrap = document.createElement('div');
            wrap.style.cssText = 'position:relative;width:100%;aspect-ratio:16/9;background:#000;border-radius:8px;overflow:hidden;';

            const iframe = document.createElement('iframe');
            iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(src) + '?rel=0&autoplay=1';
            iframe.title = title;
            iframe.style.cssText = 'width:100%;height:100%;border:0;';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            iframe.allowFullscreen = true;

            wrap.appendChild(iframe);
            modalBody.appendChild(wrap);
        } else if (kind === 'pdf') {
            const iframe = document.createElement('iframe');
            iframe.src = src + '#toolbar=1';
            iframe.title = title;
            iframe.style.cssText = 'width:100%;height:70vh;border:1px solid #e2e8f0;border-radius:8px;';
            modalBody.appendChild(iframe);
        } else if (kind === 'image') {
            const img = document.createElement('img');
            img.src = src;
            img.alt = title;
            img.style.cssText = 'display:block;max-width:100%;max-height:70vh;margin:0 auto;border-radius:8px;object-fit:contain;';
            modalBody.appendChild(img);
        } else if (kind === 'text') {
            const textBox = document.createElement('div');
            textBox.style.cssText = 'font-size:0.98rem;line-height:1.75;color:#1e293b;white-space:pre-line;';
            textBox.textContent = src;
            modalBody.appendChild(textBox);
        }

        modal.style.display = 'flex';
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.js-open-materi-modal');
        if (trigger) {
            e.preventDefault();
            openModal(trigger);
            return;
        }

        if (e.target === modal) {
            closeModal();
        }
    });

    closeBtn.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
});
</script>