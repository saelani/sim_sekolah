<div class="tab-pane" id="pengajuan" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Ajukan Izin / Absen</h2>
            <span class="dash-panel-meta">Kelas {{ $classes->name ?? '-' }}</span>
        </div>

        <div class="dash-panel-body" style="padding: 24px;">
            <p class="permohonan-sub">
                Siswa: <strong>{{ $student->name }}</strong> ({{ $student->nis }}) · Kelas: <strong>{{ $classes->name ?? '-' }}</strong>
            </p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Periksa kembali isian formulir:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formPengajuanIzin" method="POST" action="{{ route('student.attendance-request.store', ['nis' => $student->nis]) }}">
                @csrf
                <div class="form-row">
                    <label class="form-label" for="date">Tanggal Kehadiran</label>
                    <input class="form-control" type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="form-row">
                    <label class="form-label" for="status">Status Kehadiran</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Izin" @selected(old('status', 'Izin') === 'Izin')>Izin</option>
                        <option value="Sakit" @selected(old('status') === 'Sakit')>Sakit</option>
                        <option value="Hadir" @selected(old('status') === 'Hadir')>Hadir</option>
                        <option value="Alpa" @selected(old('status') === 'Alpa')>Alpa</option>
                    </select>
                </div>

                <div class="form-row">
                    <label class="form-label" for="notes">Alasan (opsional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tulis alasan izin atau sakit (contoh: izin keluarga / sakit demam)">{{ old('notes') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="button" id="btnBatalPengajuan" class="btn-cancel-tab">Batal</button>
                    <button class="btn-primary-tab" type="submit">Kirim Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>