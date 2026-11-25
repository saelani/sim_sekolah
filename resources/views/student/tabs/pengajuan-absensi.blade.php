<div class="pengajuan-box">
    <h3>Ajukan Absen / Izin</h3>
    <p class="permohonan-sub">Kirim permohonan kehadiran, izin, atau sakit.</p>

    <form id="formPengajuanAbsen" method="POST" action="{{ route('student.attendance-request.store', ['nis' => $student->nis]) }}">
        @csrf
        <div class="form-row">
            <label class="form-label" for="attendance_date">Tanggal Kehadiran</label>
            <input class="form-control" type="date" id="attendance_date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
        </div>

        <div class="form-row">
            <label class="form-label" for="attendance_status">Status Kehadiran</label>
            <select class="form-select" id="attendance_status" name="status" required>
                <option value="Izin" @selected(old('status', 'Izin') === 'Izin')>Izin</option>
                <option value="Sakit" @selected(old('status') === 'Sakit')>Sakit</option>
                <option value="Hadir" @selected(old('status') === 'Hadir')>Hadir</option>
                <option value="Alpa" @selected(old('status') === 'Alpa')>Alpa</option>
            </select>
        </div>

        <div class="form-row">
            <label class="form-label" for="attendance_notes">Alasan (opsional)</label>
            <textarea class="form-control" id="attendance_notes" name="notes" rows="3" placeholder="Contoh: izin keluarga atau sakit">{{ old('notes') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-cancel-tab" data-reset-form="formPengajuanAbsen">Batal</button>
            <button class="btn-primary-tab" type="submit">Kirim Absen</button>
        </div>
    </form>
</div>