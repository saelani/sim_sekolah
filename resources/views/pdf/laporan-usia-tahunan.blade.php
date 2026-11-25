@extends('pdf.layouts.master')

@section('title', 'Laporan Rekapitulasi Usia Siswa Tahunan')

@section('doc-title', 'LAPORAN REKAPITULASI USIA SISWA TAHUNAN')

@section('styles')
<style>
    /* Table Styling Rekapitulasi Usia Siswa Tahunan */
    table.data-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 5px !important;
    }

    table.data-table th, 
    table.data-table td {
        border: 1px solid #000000 !important;
        padding: 3px 2px !important;
        font-size: 7pt !important;
        text-align: center;
    }

    table.data-table th {
        background-color: #f2f2f2 !important;
        font-weight: bold !important;
        text-transform: uppercase;
    }

    table.data-table tr.row-total td {
        background-color: #fafafa !important;
        font-weight: bold !important;
    }

    .signature-section {
        width: 100%;
        margin-top: 20px;
        page-break-inside: avoid;
    }

    .signature-left {
        width: 45%;
        float: left;
        font-size: 8pt;
        text-align: center;
    }

    .signature-right {
        width: 45%;
        float: right;
        font-size: 8pt;
        text-align: center;
    }
</style>
@endsection

@section('meta-data')
<tr>
    <td style="width: 15%;"><strong>Tahun Pelajaran</strong></td>
    <td style="width: 35%;">: {{ $academicYear ?? '-' }}</td>
    <td style="width: 15%;"><strong>Kelas</strong></td>
    <td style="width: 35%;">: {{ $class?->name ?? '-' }}</td>
</tr>
<tr>
    <td><strong>Wali Kelas</strong></td>
    <td>: {{ $teacher?->name ?? '-' }}</td>
    <td><strong>Tanggal Cetak</strong></td>
    <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
</tr>
@endsection

@section('content')
{{-- TABEL REKAPITULASI USIA SISWA TAHUNAN --}}
<table class="data-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 18%;">Bulan</th>
            <th rowspan="2" style="width: 6%;">JK</th>
            <th colspan="{{ count($ageRanges) }}">Banyak Peserta Didik Berumur (Tahun)</th>
            <th rowspan="2" style="width: 10%;">TOTAL</th>
        </tr>
        <tr>
            @foreach($ageRanges as $age)
                <th style="width: 6%;">{{ $age }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($monthlyData as $row)
            @php
                $totalL = array_sum($row['matrix']['L'] ?? []);
                $totalP = array_sum($row['matrix']['P'] ?? []);
            @endphp
            {{-- Baris Laki-laki --}}
            <tr>
                <td rowspan="3" style="vertical-align: middle; font-weight: bold;">{{ $row['month_name'] }}</td>
                <td>L</td>
                @foreach($ageRanges as $age)
                    <td>{{ $row['matrix']['L'][$age] ?? 0 }}</td>
                @endforeach
                <td style="font-weight: bold;">{{ $totalL }}</td>
            </tr>
            {{-- Baris Perempuan --}}
            <tr>
                <td>P</td>
                @foreach($ageRanges as $age)
                    <td>{{ $row['matrix']['P'][$age] ?? 0 }}</td>
                @endforeach
                <td style="font-weight: bold;">{{ $totalP }}</td>
            </tr>
            {{-- Baris Jumlah --}}
            <tr class="row-total">
                <td>JML</td>
                @foreach($ageRanges as $age)
                    <td>{{ ($row['matrix']['L'][$age] ?? 0) + ($row['matrix']['P'][$age] ?? 0) }}</td>
                @endforeach
                <td>{{ $totalL + $totalP }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($ageRanges) + 3 }}" style="text-align: center;">Tidak ada data untuk periode ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection