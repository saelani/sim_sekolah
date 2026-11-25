@extends('pdf.layouts.master')

@section('orientation', 'landscape')
@section('title', 'Jadwal & Pemetaan Pembelajaran Mingguan - ' . $class->name)
@section('doc-title', 'LAPORAN JADWAL & PEMBELAJARAN MINGGUAN')

@section('styles')
<style>
    .badge-event {
        display: block;
        padding: 4px 6px;
        margin-bottom: 4px;
        border-radius: 3px;
        font-size: 8pt;
        line-height: 1.2;
        text-align: left;
    }
    .badge-kbm   { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-slm   { background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
    .badge-sls   { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .badge-event { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-libur { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

    .legend-box {
        margin-bottom: 10px;
        font-size: 8pt;
    }
    .legend-item {
        display: inline-block;
        padding: 2px 6px;
        margin-right: 8px;
        border-radius: 3px;
        font-weight: bold;
    }
    
    .table-data th {
        background-color: #f3f4f6;
        text-align: center;
        padding: 6px 4px;
        font-size: 8.5pt;
    }
    .table-data td {
        vertical-align: top;
        padding: 6px;
        height: 180px; /* Memberi ruang tinggi agar muat beberapa agenda */
    }
    .date-header {
        font-size: 8pt;
        color: #4b5563;
        font-weight: normal;
        display: block;
        margin-top: 2px;
    }
</style>
@endsection

@section('meta-data')
<tr>
    <td style="width: 12%;"><strong>Kelas</strong></td>
    <td style="width: 38%;">: {{ $class->name }}</td>
    <td style="width: 15%;"><strong>Periode Pekan</strong></td>
    <td style="width: 35%;">: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</td>
</tr>
<tr>
    <td><strong>Mata Pelajaran</strong></td>
    <td>: {{ $subject->name ?? 'Semua Mata Pelajaran' }}</td>
    <td><strong>Wali Kelas</strong></td>
    <td>: {{ $teacher->name ?? '-' }}</td>
</tr>
@endsection

@section('content')

{{-- KETERANGAN WARNA (LEGEND) --}}
<div class="legend-box">
    <strong>Keterangan Warna:</strong>
    <span class="legend-item badge-kbm">KBM Harian</span>
    <span class="legend-item badge-slm">SLM (Sumatif Bab)</span>
    <span class="legend-item badge-sls">SLS (Sumatif Semester)</span>
    <span class="legend-item badge-event">Event / P5</span>
    <span class="legend-item badge-libur">Libur</span>
</div>

@php
    // Kelompokkan data jadwal berdasarkan tanggal (format Y-m-d)
    $groupedSchedules = $schedules->groupBy(function($item) {
        return \Carbon\Carbon::parse($item->start_date)->format('Y-m-d');
    });

    // Buat rentang tanggal dari start_date hingga end_date (Senin - Sabtu)
    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
    
    // Filter hanya hari kerja jika diperlukan, misal Senin-Sabtu (1 - 6)
    $days = [];
    foreach ($period as $date) {
        if ($date->dayOfWeek !== \Carbon\Carbon::SUNDAY) { // Lewati hari Minggu (opsional)
            $days[] = $date;
        }
    }
    
    // Hitung lebar kolom secara dinamis berdasarkan jumlah hari
    $columnWidth = round(100 / count($days), 2) . '%';
@endphp

<table class="table-data" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            @foreach($days as $day)
                <th style="width: {{ $columnWidth }};">
                    <strong>{{ $day->translatedFormat('l') }}</strong>
                    <span class="date-header">{{ $day->translatedFormat('d/m/Y') }}</span>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($days as $day)
                @php
                    $dateKey = $day->format('Y-m-d');
                    $daySchedules = $groupedSchedules->get($dateKey, collect());
                @endphp

                <td>
                    @forelse($daySchedules as $item)
                        @php
                            $badgeClass = match($item->activity_type) {
                                'kbm'   => 'badge-kbm',
                                'slm'   => 'badge-slm',
                                'sls'   => 'badge-sls',
                                'event' => 'badge-event',
                                default => 'badge-libur',
                            };
                        @endphp

                        <div class="badge-event {{ $badgeClass }}">
                            {{-- Tampilkan Nama Mapel jika diprint untuk Semua Mata Pelajaran --}}
                            @if(!isset($subject) || !$subject) 
                                <strong>[{{ $item->subject->name ?? 'Umum' }}]</strong><br>
                            @endif

                            <strong>{{ $item->title }}</strong>

                            @if(optional($item->learningObjective)->description)
                                <br>
                                <small style="font-size: 7pt; color: #374151;">
                                    <strong>TP {{ $item->learningObjective->code ?? '' }}:</strong> 
                                    {{ $item->learningObjective->description }}
                                </small>
                            @endif

                            @if($item->description)
                                <br>
                                <small style="font-size: 6.8pt; color: #4b5563; font-style: italic;">
                                    {{ $item->description }}
                                </small>
                            @endif
                        </div>
                    @empty
                        <div style="text-align: center; color: #9ca3af; font-size: 8pt; margin-top: 20px;">
                            -
                        </div>
                    @endforelse
                </td>
            @endforeach
        </tr>
    </tbody>
</table>

@endsection