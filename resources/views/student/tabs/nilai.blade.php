@php
    $finalScoresCollection = $finalscores ?? ($finalScore ?? collect());
    $formativesCollection = $formatives ?? ($formatife ?? ($formatifs ?? collect()));
    $sumativesCollection = $sumatives ?? ($sumatif ?? collect());
    $examSessionsCollection = $examSessions ?? ($examSesion ?? ($exam_sessions ?? collect()));
    $ekskulScoresCollection = $extrakurikulerscores ?? ($extrakurikulerScores ?? ($extracurricularScores ?? collect()));
@endphp

<style>
    .tab-nilai-subtabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
    }

    .tab-nilai-subtab-btn {
        flex: 0 0 auto;
        white-space: nowrap;
        padding: 8px 14px;
        min-height: 38px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #f9fafb;
        color: #374151;
        font-size: 0.86rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
    }

    .tab-nilai-subtab-btn.active {
        background: #1a3a5c;
        color: #ffffff;
        border-color: #1a3a5c;
    }

    .tab-nilai-panel {
        display: none;
    }

    .tab-nilai-panel.active {
        display: block;
    }

    .tab-nilai-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }

    .tab-nilai-table {
        width: 100%;
        min-width: 520px;
        border-collapse: collapse;
        font-size: 0.9rem;
        background: #ffffff;
    }

    .tab-nilai-table th,
    .tab-nilai-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }

    .tab-nilai-table th {
        background: #f8fafc;
        color: #1f2937;
        font-weight: 700;
        white-space: nowrap;
    }

    .tab-nilai-table tbody tr:last-child td {
        border-bottom: none;
    }

    @media (max-width: 600px) {
        .tab-nilai-body {
            padding: 14px !important;
        }

        .tab-nilai-subtab-btn {
            padding: 7px 12px;
            font-size: 0.8rem;
        }

        .tab-nilai-table {
            min-width: 440px;
            font-size: 0.82rem;
        }

        .tab-nilai-table th,
        .tab-nilai-table td {
            padding: 8px 10px;
        }
    }
</style>

<div class="tab-pane" id="nilai" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Rekap Nilai Siswa</h2>
            <span class="dash-panel-meta">{{ $student->name ?? 'Siswa' }} · NIS {{ $student->nis ?? '-' }}</span>
        </div>

        <div class="dash-panel-body tab-nilai-body" style="padding: 24px;">
            <div class="tab-nilai-subtabs" role="tablist" aria-label="Jenis Nilai">
                <button type="button" class="tab-nilai-subtab-btn active" data-subtab="nilai-formatif" role="tab" aria-selected="true">
                    Formatif ({{ $formativesCollection->count() }})
                </button>
                <button type="button" class="tab-nilai-subtab-btn" data-subtab="nilai-sumatif" role="tab" aria-selected="false">
                    Sumatif ({{ $sumativesCollection->count() }})
                </button>
                <button type="button" class="tab-nilai-subtab-btn" data-subtab="nilai-sesi" role="tab" aria-selected="false">
                    Sesi Ujian ({{ $examSessionsCollection->count() }})
                </button>
                <button type="button" class="tab-nilai-subtab-btn" data-subtab="nilai-final" role="tab" aria-selected="false">
                    Nilai Akhir ({{ $finalScoresCollection->count() }})
                </button>
                <button type="button" class="tab-nilai-subtab-btn" data-subtab="nilai-ekskul" role="tab" aria-selected="false">
                    Ekstrakurikuler ({{ $ekskulScoresCollection->count() }})
                </button>
            </div>

            {{-- 1. Formatif --}}
            <div id="nilai-formatif" class="tab-nilai-panel active">
                @if ($formativesCollection->isEmpty())
                    <div class="portal-empty-state" style="border: none; padding: 24px 12px;">
                        <h3>Belum Ada Nilai Formatif</h3>
                        <p>Data penilaian formatif belum tersedia untuk akun ini.</p>
                    </div>
                @else
                    <div class="tab-nilai-table-wrap">
                        <table class="tab-nilai-table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Tujuan Pembelajaran (TP)</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                    <th>Ketercapaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formativesCollection as $row)
                                    @php
                                        $tpLabel = $row->learningObjective->code
                                            ?? $row->learningObjective->title
                                            ?? $row->learningObjective->name
                                            ?? $row->learningObjective->description
                                            ?? ('TP #' . ($row->learning_objective_id ?? '-'));
                                        $mapelLabel = $row->learningObjective->subject->name ?? '-';
                                        $achieved = $row->is_achieved;
                                    @endphp
                                    <tr>
                                        <td>{{ $mapelLabel }}</td>
                                        <td><strong>{{ $tpLabel }}</strong></td>
                                        <td>{{ $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-' }}</td>
                                        <td><strong>{{ $row->score ?? '-' }}</strong></td>
                                        <td>
                                            @if (!is_null($achieved))
                                                @if ((bool) $achieved)
                                                    <span style="color:#15803d;font-weight:600;">Tercapai</span>
                                                @else
                                                    <span style="color:#b91c1c;font-weight:600;">Belum Tercapai</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 2. Sumatif --}}
            <div id="nilai-sumatif" class="tab-nilai-panel">
                @if ($sumativesCollection->isEmpty())
                    <div class="portal-empty-state" style="border: none; padding: 24px 12px;">
                        <h3>Belum Ada Nilai Sumatif</h3>
                        <p>Data penilaian sumatif belum tersedia untuk akun ini.</p>
                    </div>
                @else
                    <div class="tab-nilai-table-wrap">
                        <table class="tab-nilai-table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Lingkup Sumatif</th>
                                    <th>Jenis</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sumativesCollection as $row)
                                    @php
                                        $scopeLabel = $row->sumativeScope->name
                                            ?? $row->sumativeScope->title
                                            ?? $row->sumativeScope->scope
                                            ?? '-';
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $row->subject->name ?? '-' }}</strong></td>
                                        <td>{{ $scopeLabel }}</td>
                                        <td>{{ $row->type ? ucfirst(str_replace('_', ' ', $row->type)) : '-' }}</td>
                                        <td><strong>{{ $row->score ?? '-' }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 3. Sesi Ujian --}}
            <div id="nilai-sesi" class="tab-nilai-panel">
                @if ($examSessionsCollection->isEmpty())
                    <div class="portal-empty-state" style="border: none; padding: 24px 12px;">
                        <h3>Belum Ada Sesi Ujian</h3>
                        <p>Riwayat sesi ujian CBT belum tersedia.</p>
                    </div>
                @else
                    <div class="tab-nilai-table-wrap">
                        <table class="tab-nilai-table">
                            <thead>
                                <tr>
                                    <th>Ujian</th>
                                    <th>Status</th>
                                    <th>Skor</th>
                                    <th>Total Skor</th>
                                    <th>Waktu Submit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($examSessionsCollection as $session)
                                    @php
                                        $submittedRaw = $session->submitted_at ?? $session->start_time;
                                        $submittedFormatted = '-';
                                        if (!empty($submittedRaw)) {
                                            try {
                                                $submittedFormatted = \Illuminate\Support\Carbon::parse($submittedRaw)->format('d M Y H:i');
                                            } catch (\Throwable $e) {
                                                $submittedFormatted = (string) $submittedRaw;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $session->exam->title ?? ($session->exam->name ?? ('Ujian #' . ($session->cbt_exam_id ?? '-'))) }}</strong></td>
                                        <td>{{ $session->status ? ucfirst(str_replace('_', ' ', $session->status)) : '-' }}</td>
                                        <td><strong>{{ $session->score ?? '-' }}</strong></td>
                                        <td>{{ $session->total_score ?? '-' }}</td>
                                        <td>{{ $submittedFormatted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 4. Nilai Akhir --}}
            <div id="nilai-final" class="tab-nilai-panel">
                @if ($finalScoresCollection->isEmpty())
                    <div class="portal-empty-state" style="border: none; padding: 24px 12px;">
                        <h3>Belum Ada Nilai Akhir</h3>
                        <p>Rekap nilai akhir mata pelajaran belum tersedia.</p>
                    </div>
                @else
                    <div class="tab-nilai-table-wrap">
                        <table class="tab-nilai-table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai Akhir</th>
                                    <th>Predikat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($finalScoresCollection as $row)
                                    <tr>
                                        <td><strong>{{ $row->subject->name ?? '-' }}</strong></td>
                                        <td><strong>{{ $row->final_score ?? ($row->score ?? ($row->value ?? '-')) }}</strong></td>
                                        <td>{{ $row->grade ?? ($row->predikat ?? '-') }}</td>
                                        <td>{{ $row->notes ?? ($row->description ?? '-') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 5. Ekstrakurikuler --}}
            <div id="nilai-ekskul" class="tab-nilai-panel">
                @if ($ekskulScoresCollection->isEmpty())
                    <div class="portal-empty-state" style="border: none; padding: 24px 12px;">
                        <h3>Belum Ada Nilai Ekstrakurikuler</h3>
                        <p>Data nilai atau predikat ekstrakurikuler belum tersedia.</p>
                    </div>
                @else
                    <div class="tab-nilai-table-wrap">
                        <table class="tab-nilai-table">
                            <thead>
                                <tr>
                                    <th>Ekstrakurikuler</th>
                                    <th>Predikat / Nilai</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ekskulScoresCollection as $ekskul)
                                    <tr>
                                        <td><strong>{{ $ekskul->extracurricular->name ?? ($ekskul->extrakurikuler->name ?? ($ekskul->name ?? '-')) }}</strong></td>
                                        <td><strong>{{ $ekskul->grade ?? ($ekskul->predikat ?? ($ekskul->score ?? '-')) }}</strong></td>
                                        <td>{{ $ekskul->notes ?? ($ekskul->description ?? '-') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pane = document.getElementById('nilai');
    if (!pane) return;

    const btns = pane.querySelectorAll('.tab-nilai-subtab-btn');
    const panels = pane.querySelectorAll('.tab-nilai-panel');

    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-subtab');
            btns.forEach(function (b) {
                const active = b === btn;
                b.classList.toggle('active', active);
                b.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.id === targetId);
            });
            this.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
        });
    });
});
</script>