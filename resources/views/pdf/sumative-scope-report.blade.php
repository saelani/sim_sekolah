@extends('pdf.layouts.master')

@section('orientation', 'portrait')
@section('title', 'Laporan Lingkup Materi - ' . $subject->name)
@section('doc-title', 'PEMETAAN LINGKUP MATERI SUMATIF')

@section('meta-data')
    <tr>
        <td style="width: 18%; font-weight: bold;">Mata Pelajaran</td>
        <td style="width: 2%;">:</td>
        <td style="width: 30%;">{{ $subject->name }}</td>
        
        <td style="width: 18%; font-weight: bold;">Fase</td>
        <td style="width: 2%;">:</td>
        <td style="width: 30%;">
            {{ $selectedPhase ? 'Fase ' . $selectedPhase : 'Semua Fase' }}
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Tahun Ajaran</td>
        <td>:</td>
        <td>{{ date('Y') }}/{{ date('Y') + 1 }}</td>
        
        <td style="font-weight: bold;">Semester</td>
        <td>:</td>
        <td>
            {{ $selectedSemester ? 'Semester ' . $selectedSemester : 'Semua Semester' }}
        </td>
    </tr>
@endsection

@section('content')
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Lingkup Materi / Bab</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 15%;">Fase</th>
                <th style="width: 15%;">Semester</th>
                <th style="width: 15%;">Guru Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sumativeScopes as $index => $scope)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $scope->name ?? '-' }}</td>
                    <td class="text-center">{{ $scope->schoolClass->name ?? '-' }}</td>
                    <td class="text-center">Fase {{ $scope->phase ?? '-' }}</td>
                    <td class="text-center">{{ $scope->semester ?? '-' }}</td>
                    <td class="text-center">{{ $scope->teacher->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 10px;">
                        Data Lingkup Materi belum tersedia untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection