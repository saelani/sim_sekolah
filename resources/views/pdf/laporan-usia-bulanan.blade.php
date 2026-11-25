@extends('pdf.layouts.master')

@section('title', 'Laporan Rekapitulasi Usia Siswa Bulanan')

@section('doc-title', 'LAPORAN REKAPITULASI USIA SISWA')

@section('styles')
<style>
    /* Styling Tabel Data Utama */
    table.table-data {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 5px !important;
    }

    table.table-data th, 
    table.table-data td {
        border: 1px solid #000000 !important;
        padding: 4px 3px !important;
        font-size: 8pt !important;
        text-align: center;
    }

    table.table-data th {
        background-color: #f2f2f2 !important;
        font-weight: bold !important;
        text-transform: uppercase;
    }

    table.table-data tr.row-total td {
        background-color: #fafafa !important;
        font-weight: bold !important;
    }
</style>
@endsection

@section('meta-data')
<tr>
    <td style="width: 12%;"><strong>Kelas</strong></td>
    <td style="width: 38%;">: {{ $class?->name ?? '-' }}</td>
    <td style="width: 12%;"><strong>Bulan / Tahun</strong></td>
    <td style="width: 38%;">: {{ $monthName ?? '-' }}</td>
</tr>
<tr>
    <td><strong>Wali Kelas</strong></td>
    <td>: {{ $teacher?->name ?? '-' }}</td>
    <td><strong>Tanggal Cetak</strong></td>
    <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
</tr>
@endsection

@section('content')
@php
    $totalL = array_sum($matrix['L'] ?? []);
    $totalP = array_sum($matrix['P'] ?? []);
    $totalJml = $totalL + $totalP;
@endphp

<table class="table-data">
    <thead>
        <tr>
            <th>JK</th>
            @foreach($ageRanges as $age)
                <th>{{ $age }} Thn</th>
            @endforeach
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-weight: bold;">L</td>
            @foreach($ageRanges as $age)
                <td>{{ $matrix['L'][$age] ?? 0 }}</td>
            @endforeach
            <td style="font-weight: bold;">{{ $totalL }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">P</td>
            @foreach($ageRanges as $age)
                <td>{{ $matrix['P'][$age] ?? 0 }}</td>
            @endforeach
            <td style="font-weight: bold;">{{ $totalP }}</td>
        </tr>
        <tr class="row-total">
            <td style="font-weight: bold;">JML</td>
            @foreach($ageRanges as $age)
                <td style="font-weight: bold;">{{ ($matrix['L'][$age] ?? 0) + ($matrix['P'][$age] ?? 0) }}</td>
            @endforeach
            <td style="font-weight: bold;">{{ $totalJml }}</td>
        </tr>
    </tbody>
</table>
@endsection