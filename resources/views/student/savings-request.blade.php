<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Tabungan | {{ config('app.name', 'Sekolah') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('css/public-portal.css')))
        <link rel="stylesheet" href="{{ asset('css/public-portal.css') }}">
    @endif
    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; color: #1a1a1a; margin: 0; padding-bottom: 40px; }
        .request-wrap { max-width: 640px; margin: 0 auto; padding: 24px 16px; }
        .request-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 24px; }
        .request-card h1 { margin: 0 0 6px; font-size: 1.35rem; }
        .request-card .sub { color: #6b7280; font-size: .9rem; margin-bottom: 20px; }
        .form-row { margin-bottom: 18px; }
        .form-label { display: block; font-weight: 600; font-size: .85rem; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .95rem; box-sizing: border-box; }
        .form-control:focus { outline: 2px solid #3b82f6; border-color: #3b82f6; }
        .form-select { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .95rem; background: #fff; }
        .btn-group { display: flex; gap: 10px; }
        .btn-group .btn-primary { flex: 1; }
        .btn-primary { background: #1a3a5c; color: #fff; border: none; padding: 12px 22px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-primary:hover { background: #142b47; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: .92rem; }
        .alert-success { background: #ecfdf5; color: #15803d; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    </style>
</head>
<body>
<div class="request-wrap">
    <div class="request-card">
        <h1>Ajukan Tabungan</h1>
        <p class="sub">Siswa: {{ $student->name }} ({{ $student->nis }}) · Kelas: {{ $classes->name ?? '-' }}</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('student.savings-request.store', ['nis' => $student->nis]) }}">
            @csrf
            <div class="form-row">
                <label class="form-label" for="date">Tanggal Transaksi</label>
                <input class="form-control" type="date" id="date" name="date" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div class="form-row">
                <label class="form-label" for="type">Tipe Transaksi</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="deposit">Setoran (Deposit)</option>
                    <option value="withdrawal">Penarikan (Withdrawal)</option>
                </select>
            </div>

            <div class="form-row">
                <label class="form-label" for="amount"> Nominal (Rp)</label>
                <input class="form-control" type="number" id="amount" name="amount" min="0.01" step="0.01" placeholder="0.00" required>
            </div>

            <div class="form-row">
                <label class="form-label" for="notes">Keterangan (opsional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan transaksi, contoh: setoran bulanan / pinjaman"></textarea>
            </div>

            <div class="form-row" style="margin-top: 22px;">
                <button class="btn-primary" type="submit">Kirim Permohonan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
