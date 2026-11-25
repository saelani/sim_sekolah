@extends('pdf.layouts.master')

@section('title', 'Laporan Rekapitulasi Presensi Bulanan Siswa')

@section('doc-title', 'REKAPITULASI KETIDAKHADIRAN SISWA PER BULAN')

@section('styles')
<style>
    /* Table Styling Matriks Presensi Bulanan (Landscape A4) */
    table.data-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 5px !important;
    }

    table.data-table th, 
    table.data-table td {
        border: 1px solid #000000 !important;
        padding: 3px 1px !important;
        font-size: 6.5pt !important; /* Font disesuaikan agar 31 kolom muat dengan rapi di A4 Landscape */
        text-align: center;
    }

    table.data-table th {
        background-color: #f2f2f2 !important;
        font-weight: bold !important;
        text-transform: uppercase;
    }

    table.data-table td.student-name {
        text-align: left !important;
        padding-left: 4px !important;
        white-space: nowrap;
    }

    .bg-weekend {
        background-color: #e2e2e2 !important;
    }

    table.data-table tfoot td {
        background-color: #fafafa !important;
        font-weight: bold !important;
    }

    .signature-section {
        width: 100%;
        margin-top: 20px;
        page-break-inside: avoid;
    }

    .signature-box {
        width: 45%;
        font-size: 8pt;
    }

    .legend-box {
        margin-top: 8px;
        font-size: 7pt;
    }
</style>
@endsection

@section('meta-data')
<tr>
    <td style="width: 12%;"><strong>Kelas</strong></td>
    <td style="width: 38%;">: {{ $class?->name ?? '-' }}</td>
    <td style="width: 12%;"><strong>Bulan / Tahun</strong></td>
    <td style="width: 38%;">: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</td>
</tr>
<tr>
    <td><strong>Wali Kelas</strong></td>
    <td>: {{ $teacher?->name ?? '-' }}</td>
    <td><strong>Tanggal Cetak</strong></td>
    <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
</tr>
@endsection

@section('content')
{{-- TABEL MATRIKS PRESENSI BULANAN --}}
<table class="data-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 3%;">No</th>
            <th rowspan="2" style="width: 10%;">NISN / NIS</th>
            <th rowspan="2" style="width: 18%;">Nama Siswa</th>
            <th colspan="{{ $daysInMonth }}">Tanggal</th>
            <th colspan="3">Rekap</th>
            <th rowspan="2" style="width: 4%;">Jml</th>
        </tr>
        <tr>
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                @endphp
                <th class="{{ $isWeekend ? 'bg-weekend' : '' }}">{{ $d }}</th>
            @endfor
            <th style="width: 2.5%;">S</th>
            <th style="width: 2.5%;">I</th>
            <th style="width: 2.5%;">A</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $student)
            @php
                $sakitCount = 0;
                $izinCount = 0;
                $alpaCount = 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->nisn ?? $student->nis ?? '-' }}</td>
                <td class="student-name">{{ $student->name }}</td>

                @for ($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $status = $attendanceData[$student->id][$dateKey] ?? null;
                        $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();

                        if ($status === 'S') $sakitCount++;
                        elseif ($status === 'I') $izinCount++;
                        elseif ($status === 'A') $alpaCount++;
                    @endphp
                    <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                        {{ $status ?? '' }}
                    </td>
                @endfor

                <td>{{ $sakitCount ?: '-' }}</td>
                <td>{{ $izinCount ?: '-' }}</td>
                <td>{{ $alpaCount ?: '-' }}</td>
                <td style="font-weight: bold;">
                    {{ ($sakitCount + $izinCount + $alpaCount) ?: '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $daysInMonth + 7 }}" class="text-center">Tidak ada data siswa untuk periode ini.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL KETIDAKHADIRAN HARIAN:</td>
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $dailyTotal = $dailyTotals[$dateKey] ?? 0;
                    $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                @endphp
                <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                    {{ $dailyTotal ?: '' }}
                </td>
            @endfor
            <td>{{ $grandTotalSakit ?: 0 }}</td>
            <td>{{ $grandTotalIzin ?: 0 }}</td>
            <td>{{ $grandTotalAlpa ?: 0 }}</td>
            <td>{{ $grandTotalSakit + $grandTotalIzin + $grandTotalAlpa }}</td>
        </tr>
    </tfoot>
</table>

{{-- KETERANGAN / LEGEND --}}
<div class="legend-box">
    <strong>Keterangan:</strong>
    <span style="margin-left: 10px;"><strong>S</strong> = Sakit</span>
    <span style="margin-left: 10px;"><strong>I</strong> = Izin</span>
    <span style="margin-left: 10px;"><strong>A</strong> = Alpa</span>
    <span style="margin-left: 15px;">
        <span style="display:inline-block; width:10px; height:10px; background-color:#e2e2e2; border:1px solid #999; vertical-align:middle;"></span> = Akhir Pekan (Sabtu / Minggu)
    </span>
</div>


@endsection