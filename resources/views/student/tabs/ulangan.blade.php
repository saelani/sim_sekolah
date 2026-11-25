<div class="tab-pane" id="ulangan" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Ulangan / CBT</h2>
            <span class="dash-panel-meta">{{ $exams->count() }} ujian tersedia</span>
        </div>

        <div class="dash-panel-body">
            @if ($exams->isEmpty())
                <div class="portal-empty-state" style="border: none; padding: 32px 16px;">
                    <h3>Belum Ada Ujian CBT yang Tersedia</h3>
                    <p>Ujian berbasis komputer akan muncul di sini saat sudah dijadwalkan.</p>
                </div>
            @else
                @php
                    $studentNis = trim((string) ($student->nis ?? auth('student')->user()?->nis ?? ''));
                @endphp

                @if ($studentNis === '')
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 0.86rem;">
                        <strong>Perhatian:</strong> Data <strong>NIS</strong> pada akun siswa ini masih kosong di database, sehingga tombol ujian belum dapat diakses.
                    </div>
                @endif

                <div class="portal-grid">
                    @foreach ($exams as $exam)
                        @php
                            $subjectCode = $exam->subject?->code ?? $exam->Subject?->code ?? '-';
                            $subjectName = $exam->subject?->name ?? $exam->Subject?->name ?? '-';
                            $examToken   = trim((string) ($exam->token ?? ''));
                            $canStart    = $studentNis !== '' && $examToken !== '';
                        @endphp

                        <article class="portal-card dash-exam-card">
                            <div class="portal-card-body">
                                <div>
                                    <span class="dash-exam-subject">
                                        {{ $subjectCode }} &middot; {{ $subjectName }}
                                    </span>
                                </div>

                                <h3 class="dash-exam-title">{{ $exam->title }}</h3>

                                <div class="dash-exam-meta">
                                    <div>Durasi: <strong>{{ $exam->duration_minutes }} menit</strong></div>
                                    <div>Mulai: <strong>{{ \Illuminate\Support\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}</strong></div>
                                    <div>Selesai: <strong>{{ \Illuminate\Support\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}</strong></div>
                                </div>
                            </div>

                            <footer class="portal-card-footer">
                                @if ($canStart)
                                    <a
                                        href="{{ route('student.exam.take', ['nis' => $studentNis, 'token' => $examToken, 'id' => $exam->id]) }}"
                                        class="portal-btn-primary"
                                        style="width: 100%;"
                                    >
                                        <span>Mulai Ujian</span>
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </a>
                                @else
                                    <button
                                        type="button"
                                        class="portal-btn-primary"
                                        style="width: 100%; opacity: 0.55; cursor: not-allowed;"
                                        disabled
                                    >
                                        {{ $studentNis === '' ? 'NIS Siswa Belum Diisi' : 'Token Ujian Belum Tersedia' }}
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