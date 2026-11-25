@extends('pdf.layouts.master')

@section('orientation', 'portrait')
@section('title', 'Laporan Tujuan Pembelajaran - ' . $subject->name)
@section('doc-title', 'PEMETAAN TUJUAN PEMBELAJARAN (TP)')

@section('meta-data')
    <tr>
        <td style="width: 18%; font-weight: bold;">Mata Pelajaran</td>
        <td style="width: 2%;">:</td>
        <td style="width: 30%;">{{ $subject->name }}</td>
        
        <td style="width: 18%; font-weight: bold;">Tingkat Kelas</td>
        <td style="width: 2%;">:</td>
        <td style="width: 30%;">
            {{ $selectedLevel ? 'Kelas ' . $selectedLevel : 'Semua Kelas' }}
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
                <th style="width: 12%;">Kode TP</th>
                <th style="width: 25%;">Bab / Lingkup Materi</th>
                <th style="width: 8%;">Kelas</th>
                <th style="width: 8%;">Fase</th>
                <th style="width: 8%;">Sem.</th>
                <th style="width: 34%;">Deskripsi Tujuan Pembelajaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($learningObjectives as $index => $tp)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center font-bold">{{ $tp->code ?? '-' }}</td>
                    <td>{{ $tp->sumativeScope->name ?? '-' }}</td>
                    <td class="text-center">Kelas {{ $tp->level }}</td>
                    <td class="text-center">{{ $tp->phase }}</td>
                    <td class="text-center">{{ $tp->semester }}</td>
                    <td>{{ $tp->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 10px;">
                        Data Tujuan Pembelajaran belum tersedia untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection