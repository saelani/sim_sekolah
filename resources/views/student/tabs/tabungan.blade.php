<div class="tab-pane active" id="tabungan" role="tabpanel">
    <div class="portal-card" style="transform: none;">
        <div class="dash-panel-head">
            <h2>Riwayat Tabungan Siswa</h2>
            <span class="dash-panel-meta">{{ $savings->count() }} transaksi tercatat</span>
        </div>

        <div class="dash-panel-body">
            @if ($savings->isNotEmpty())
                {{-- Kartu Highlight Saldo --}}
                <div class="savings-highlight">
                    <div>
                        <span class="savings-highlight-label">Total Saldo Tabungan Terkumpul</span>
                        <div style="font-size: 0.78rem; color: rgba(255, 255, 255, 0.75); margin-top: 2px;">
                            Diperbarui berdasarkan {{ $savings->count() }} riwayat transaksi
                        </div>
                    </div>
                    <span class="savings-highlight-amount">
                        Rp {{ number_format($totalSavings ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Daftar 10 Transaksi Terbaru --}}
                <div>
                    @foreach ($savings->sortByDesc('date')->take(10) as $s)
                        @php
                            $rawType = mb_strtolower(trim($s->type ?? 'setoran'));
                            $isWithdrawal = in_array($rawType, ['penarikan', 'tarik', 'withdraw', 'withdrawal', 'keluar', 'out', 'debit'], true);
                            $formattedDate = !empty($s->date)
                                ? \Illuminate\Support\Carbon::parse($s->date)->format('d/m/Y')
                                : '-';
                            $noteText = $s->description ?? $s->note ?? null;
                        @endphp

                        <div class="dash-list-row">
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <span class="dash-list-date" style="font-weight: 600; color: var(--portal-text);">
                                    {{ $formattedDate }}
                                </span>
                                @if (!empty($noteText))
                                    <span style="font-size: 0.78rem; color: var(--portal-muted);">
                                        {{ $noteText }}
                                    </span>
                                @endif
                            </div>

                            <div class="dash-list-amount">
                                <span
                                    class="dash-type-tag"
                                    style="{{ $isWithdrawal
                                        ? 'background: #fef2f2; color: #b91c1c;'
                                        : 'background: #ecfdf5; color: #15803d;' }}"
                                >
                                    {{ ucfirst($s->type ?? 'Setoran') }}
                                </span>

                                <span style="font-variant-numeric: tabular-nums; color: {{ $isWithdrawal ? '#b91c1c' : '#15803d' }};">
                                    {{ $isWithdrawal ? '-' : '+' }} Rp {{ number_format($s->amount ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($savings->count() > 10)
                    <div class="dash-list-row" style="margin-top: 6px; padding-top: 14px; border-top: 1px dashed var(--portal-border);">
                        <span class="dash-list-date">... dan {{ $savings->count() - 10 }} transaksi sebelumnya</span>
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--portal-navy-light);">
                            Menampilkan 10 transaksi terbaru
                        </span>
                    </div>
                @endif
            @else
                <div class="portal-empty-state" style="border: none; padding: 36px 16px;">
                    <div class="portal-empty-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Data Tabungan</h3>
                    <p>Belum ada penyetoran atau transaksi tabungan yang tercatat untuk siswa ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>