<div class="tab-pane" id="presensi" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Kehadiran (30 Hari Terakhir)</h2>
        </div>

        <div class="dash-panel-body">
            @if ($attendances->isNotEmpty())
                <div class="dash-table-wrap">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Guru</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances->sortByDesc('date')->take(30) as $a)
                                <tr>
                                    <td style="font-weight: 600; color: var(--portal-navy);">{{ $a->date }}</td>
                                    <td>{{ $a->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $a->teacher->name ?? '-' }}</td>
                                    <td>
                                        @if ($a->status === 'Hadir')
                                            <span class="status-badge status-hadir">Hadir</span>
                                        @elseif ($a->status === 'Sakit')
                                            <span class="status-badge status-sakit">Sakit</span>
                                        @elseif ($a->status === 'Izin')
                                            <span class="status-badge status-izin">Izin</span>
                                        @else
                                            <span class="status-badge status-alpa">{{ $a->status ?? 'Alpa' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="portal-empty-state" style="border: none; padding: 32px 16px;">
                    <h3>Belum Ada Data Kehadiran</h3>
                    <p>Belum ada riwayat presensi yang tercatat untuk siswa ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>