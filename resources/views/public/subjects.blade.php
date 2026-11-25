@extends('layouts.public')

@section('title', 'Mata Pelajaran | ' . ($profile->school_name ?? config('app.name', 'Sekolah')))

@section('page-style')
<style>
    .hero { background: linear-gradient(135deg, #1a3a5c 0%, #2c5f8a 100%); color: #fff; padding: 56px 0; text-align: center; }
    .hero h1 { font-size: 2.2rem; margin-bottom: 10px; }
    .hero p { opacity: .9; font-size: 1.05rem; }
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .card-subject { background: #fff; border: 1px solid #e3e0d8; border-radius: 12px; padding: 20px; text-align: center; }
    .card-subject .name { font-weight: 700; color: #2b2b2b; }
    .card-subject .code { font-size: .85rem; color: #7a7a7a; }
    .card-subject .sub { color: #7a7a7a; font-size: .85rem; margin-top: 6px; }
    .empty { text-align: center; padding: 48px 0; color: #7a7a7a; }
    .empty h3 { color: #1a3a5c; margin-bottom: 8px; }
</style>
@endsection

@section('content')
<section class="hero">
    <div class="container">
        <h1>Mata Pelajaran</h1>
        <p>Daftar mata pelajaran di {{ $profile->school_name ?? config('app.name', 'Sekolah') }}</p>
    </div>
</section>

<section class="container pt-3">
    @if ($subjects->isNotEmpty())
        <div class="grid">
            @foreach ($subjects as $subject)
                <div class="card-subject">
                    <div class="name">{{ $subject->name }}</div>
                    <div class="code">Kode: {{ $subject->code ?? '-' }}</div>
                    <div class="sub">{{ $subject->category ?? '' }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty">
            <h3>Belum ada data mata pelajaran</h3>
            <p>Tidak ada mata pelajaran yang terdaftar di sistem.</p>
        </div>
    @endif
</section>
@endsection
