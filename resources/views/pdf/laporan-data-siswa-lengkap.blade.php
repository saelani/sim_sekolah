@extends('pdf.layouts.master')

@section('title', 'Laporan Lengkap Data Siswa')

@section('doc-title', 'LAPORAN INDUK & REKAPITULASI DATA SISWA')

@section('styles')
<style>
    /* Styling Informasi Kelas */
    table.meta-info {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 10px !important;
    }
    table.meta-info td {
        padding: 2px 0 !important;
        border: none !important;
        font-size: 8.5pt !important;
        vertical-align: top !important;
    }

    /* Styling Judul Sub Bagian */
    .section-subtitle {
        font-size: 9.5pt;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    /* Styling Tabel Umum / Data */
    table.table-data {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 10px !important;
    }

    table.table-data th, 
    table.table-data td {
        border: 1px solid #000000 !important;
        padding: 4px 5px !important;
        font-size: 8pt !important;
        vertical-align: middle;
    }

    table.table-data th {
        background-color: #f2f2f2 !important;
        font-weight: bold !important;
        text-align: center;
        text-transform: uppercase;
    }
</style>
@endsection

@section('meta-data')
@endsection

@section('content')
{{-- Informasi Kelas & Wali Kelas --}}
<table class="meta-info">
    <tr>
        <td style="width: 12%;"><strong>Kelas</strong></td>
        <td style="width: 38%;">: {{ $class?->name ?? '-' }}</td>
        <td style="width: 15%;"><strong>Tahun Ajaran</strong></td>
        <td style="width: 35%;">: {{ date('Y') }}/{{ date('Y') + 1 }}</td>
    </tr>
    <tr>
        <td><strong>Wali Kelas</strong></td>
        <td>: {{ $teacher?->name ?? '-' }}</td>
        <td><strong>Tanggal Cetak</strong></td>
        <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
    </tr>
</table>

{{-- Bagian 1: Rekapitulasi Jumlah Siswa --}}
<div class="section-subtitle">1. Rekapitulasi Jumlah Siswa</div>
<table class="table-data">
    <thead>
        <tr>
            <th style="width: 10%;">No.</th>
            <th style="width: 50%;">Keterangan / Uraian</th>
            <th style="width: 13%;">Laki-laki (L)</th>
            <th style="width: 13%;">Perempuan (P)</th>
            <th style="width: 14%;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>Jumlah Siswa Aktif</td>
            <td class="text-center">{{ $totalL }}</td>
            <td class="text-center">{{ $totalP }}</td>
            <td class="text-center font-bold">{{ $totalJml }}</td>
        </tr>
    </tbody>
</table>

{{-- Bagian 2: Daftar Nama Siswa (Data Induk) --}}
<div class="section-subtitle">2. Daftar Nama Peserta Didik</div>
<table class="table-data">
    <thead>
        <tr>
            <th style="width: 6%;">No.</th>
            <th style="width: 18%;">NIS / NISN</th>
            <th style="width: 30%;">Nama Lengkap</th>
            <th style="width: 7%;">JK</th>
            <th style="width: 24%;">Tempat, Tanggal Lahir</th>
            <th style="width: 15%;">Orang Tua / Wali</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $student)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
                <td>{{ $student->name }}</td>
                <td class="text-center">{{ $student->gender }}</td>
                <td>{{ $student->pob ?? '-' }}, {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->translatedFormat('d-m-Y') : '-' }}</td>
                <td>{{ $student->parent_name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 10px; font-style: italic;">Belum ada data siswa terdaftar di kelas ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection