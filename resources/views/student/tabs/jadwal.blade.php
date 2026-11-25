@php
    $standardDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $presentDays = $schedules->pluck('day_name')->filter()->unique()->values()->all();
    $activeDays = array_values(array_filter(
        $standardDays,
        fn ($day) => in_array($day, $presentDays, true)
    ));
    if (empty($activeDays)) {
        $activeDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    }

    // Kelompokkan jadwal berdasarkan rentang jam (baris = Jam, kolom = Hari)
    $timeSlots = $schedules
        ->map(function ($sc) {
            $start = substr((string) ($sc->start_time ?? ''), 0, 5);
            $end = substr((string) ($sc->end_time ?? ''), 0, 5);
            return trim($start . ($end ? ' - ' . $end : ''));
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $scheduleGrid = [];
    foreach ($schedules as $sc) {
        $start = substr((string) ($sc->start_time ?? ''), 0, 5);
        $end = substr((string) ($sc->end_time ?? ''), 0, 5);
        $slotKey = trim($start . ($end ? ' - ' . $end : ''));
        $dayKey = (string) ($sc->day_name ?? '');
        if ($slotKey !== '' && $dayKey !== '') {
            $scheduleGrid[$slotKey][$dayKey][] = $sc;
        }
    }
@endphp

<style>
    .jadwal-matrix-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
    }

    .jadwal-matrix-table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .jadwal-matrix-table th,
    .jadwal-matrix-table td {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }

    .jadwal-matrix-table thead th {
        background: #f8fafc;
        color: #111827;
        font-weight: 700;
        white-space: nowrap;
    }

    .jadwal-jam-col {
        white-space: nowrap;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--portal-navy, #1a3a5c);
        background: #fcfcfd;
        position: sticky;
        left: 0;
        z-index: 1;
    }

    .jadwal-slot-item {
        padding: 6px 8px;
        border-radius: 7px;
        background: #f8fafc;
        border-left: 3px solid #1a3a5c;
    }

    .jadwal-slot-item + .jadwal-slot-item {
        margin-top: 6px;
    }

    .jadwal-mapel-title {
        font-weight: 600;
        color: #111827;
        line-height: 1.35;
    }

    .jadwal-room-meta {
        margin-top: 3px;
        font-size: 0.78rem;
        color: #6b7280;
    }

    .jadwal-empty-slot {
        color: #cbd5e1;
        font-size: 0.82rem;
    }

    @media (max-width: 600px) {
        #jadwal .dash-panel-body {
            padding: 12px;
        }

        .jadwal-matrix-table {
            min-width: 560px;
            font-size: 0.8rem;
        }

        .jadwal-matrix-table th,
        .jadwal-matrix-table td {
            padding: 8px;
        }

        .jadwal-jam-col {
            font-size: 0.76rem;
        }

        .jadwal-slot-item {
            padding: 5px 6px;
        }
    }
</style>

<div class="tab-pane" id="jadwal" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Jadwal Pelajaran</h2>
            <span class="dash-panel-meta">Kelas {{ $classes->name ?? '-' }}</span>
        </div>

        <div class="dash-panel-body">
            @if ($schedules->isEmpty())
                <div class="portal-empty-state" style="border: none; padding: 32px 16px;">
                    <h3>Belum Ada Data Jadwal</h3>
                    <p>Belum ada jadwal pelajaran yang diatur untuk kelas ini.</p>
                </div>
            @else
                <div class="jadwal-matrix-wrap">
                    <table class="jadwal-matrix-table">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                @foreach ($activeDays as $day)
                                    <th>{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($timeSlots as $slot)
                                <tr>
                                    <td class="jadwal-jam-col">{{ $slot }}</td>
                                    @foreach ($activeDays as $day)
                                        @php
                                            $items = $scheduleGrid[$slot][$day] ?? [];
                                        @endphp
                                        <td>
                                            @if (empty($items))
                                                <span class="jadwal-empty-slot">—</span>
                                            @else
                                                @foreach ($items as $sc)
                                                    <div class="jadwal-slot-item">
                                                        <div class="jadwal-mapel-title">
                                                            {{ $sc->subject->code ?? '-' }} · {{ $sc->subject->name ?? '-' }}
                                                        </div>
                                                        @if (!empty($sc->room_name))
                                                            <div class="jadwal-room-meta">Ruang: {{ $sc->room_name }}</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>