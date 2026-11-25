<div class="pengajuan-box">
    <h3>Ajukan Tabungan</h3>
    <p class="permohonan-sub">Kirim pengajuan transaksi tabungan siswa.</p>

    <form id="formPengajuanTabungan" method="POST" action="{{ route('student.savings-request.store', ['nis' => $student->nis]) }}">
        @csrf
        <div class="form-row">
            <label class="form-label" for="savings_date">Tanggal Transaksi</label>
            <input class="form-control" type="date" id="savings_date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
        </div>

        <div class="form-row">
            <label class="form-label" for="savings_type">Jenis Pengajuan</label>
            <select class="form-select" id="savings_type" name="type" required>
                <option value="Setoran" @selected(old('type', 'Setoran') === 'Setoran')>Setoran</option>
                <option value="Penarikan" @selected(old('type') === 'Penarikan')>Penarikan</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label" for="savings_amount">Nominal (Rp)</label>
            <input class="form-control" type="number" min="1000" step="500" id="savings_amount" name="amount" value="{{ old('amount') }}" placeholder="Contoh: 10000" required>
        </div>

        <div class="form-row">
            <label class="form-label" for="savings_notes">Catatan (opsional)</label>
            <textarea class="form-control" id="savings_notes" name="notes" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-cancel-tab" data-reset-form="formPengajuanTabungan">Batal</button>
            <button class="btn-primary-tab" type="submit">Kirim Tabungan</button>
        </div>
    </form>
</div>