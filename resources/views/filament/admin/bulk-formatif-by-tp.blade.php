<x-filament-panels::page>
    <style>
        .excel-container {
            font-family: 'Segoe UI', Calibri, Inter, system-ui, -apple-system, sans-serif;
            border: 1px solid #bfbfbf;
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            margin-top: 1.5rem;
        }
        .dark .excel-container {
            border-color: #374151;
            background-color: #111827;
        }

        /* Ribbon Hijau Khas Excel */
        .excel-ribbon {
            background-color: #107c41;
            color: #ffffff;
            padding: 10px 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .excel-ribbon-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        .excel-badge-status {
            background-color: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 9999px;
            font-weight: 500;
        }
        .excel-btn-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .excel-kktp-box {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: rgba(0, 0, 0, 0.18);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
        }
        .excel-kktp-input {
            width: 52px;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: #ffffff;
            color: #111827;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
        }
        .excel-btn-secondary {
            background-color: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s;
        }
        .excel-btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.28);
        }
        .excel-btn-save {
            background-color: #ffffff;
            color: #107c41;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
            transition: background-color 0.15s;
        }
        .excel-btn-save:hover {
            background-color: #ecfdf5;
        }
        .excel-btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Formula Bar */
        .excel-formula-bar {
            display: flex;
            align-items: center;
            background-color: #f3f2f1;
            border-bottom: 1px solid #d2d0ce;
            height: 34px;
            font-size: 12px;
        }
        .dark .excel-formula-bar {
            background-color: #1f2937;
            border-color: #374151;
        }
        .excel-name-box {
            width: 76px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #323130;
            background-color: #ffffff;
            border-right: 1px solid #d2d0ce;
            user-select: none;
            flex-shrink: 0;
        }
        .dark .excel-name-box {
            background-color: #111827;
            color: #f3f4f6;
            border-color: #374151;
        }
        .excel-fx-label {
            padding: 0 12px;
            font-family: Georgia, serif;
            font-style: italic;
            font-weight: 700;
            color: #605e5c;
            border-right: 1px solid #d2d0ce;
            height: 100%;
            display: flex;
            align-items: center;
            user-select: none;
            flex-shrink: 0;
        }
        .dark .excel-fx-label {
            color: #9ca3af;
            border-color: #374151;
        }
        .excel-formula-content {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 8px;
            overflow: hidden;
        }
        .excel-formula-input {
            flex: 1;
            border: none !important;
            outline: none !important;
            background: transparent;
            font-family: monospace;
            font-size: 12.5px;
            color: #111827;
            padding: 0;
            box-shadow: none !important;
        }
        .dark .excel-formula-input {
            color: #f9fafb;
        }

        /* Tabel Grid Excel */
        .excel-scroll-area {
            max-height: 68vh;
            overflow: auto;
            position: relative;
        }
        .excel-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 13px;
        }
        .excel-table th,
        .excel-table td {
            border-right: 1px solid #d4d4d4;
            border-bottom: 1px solid #d4d4d4;
            padding: 0;
            height: 32px;
            box-sizing: border-box;
        }
        .dark .excel-table th,
        .dark .excel-table td {
            border-color: #374151;
        }

        /* Header Huruf Kolom (A, B, C, D) */
        .excel-col-letter {
            background-color: #f3f2f1;
            color: #484644;
            font-weight: 600;
            font-size: 11px;
            text-align: center;
            height: 24px !important;
            position: sticky;
            top: 0;
            z-index: 20;
            user-select: none;
        }
        .dark .excel-col-letter {
            background-color: #1f2937;
            color: #9ca3af;
        }
        .excel-col-letter.is-active {
            background-color: #d2e8dc !important;
            color: #107c41 !important;
            border-bottom: 2px solid #107c41 !important;
        }
        .dark .excel-col-letter.is-active {
            background-color: #064e3b !important;
            color: #34d399 !important;
        }

        /* Header Nama Kolom */
        .excel-field-header {
            background-color: #f8f9fa;
            color: #201f1e;
            font-weight: 700;
            font-size: 12px;
            padding: 6px 10px !important;
            position: sticky;
            top: 24px;
            z-index: 20;
            text-align: left;
            user-select: none;
        }
        .dark .excel-field-header {
            background-color: #18212f;
            color: #f3f4f6;
        }

        /* Sticky Kolom Kiri */
        .sticky-col-0 { position: sticky; left: 0; z-index: 15; width: 46px; min-width: 46px; }
        .sticky-col-1 { position: sticky; left: 46px; z-index: 15; width: 120px; min-width: 120px; }
        .sticky-col-2 { position: sticky; left: 166px; z-index: 15; min-width: 250px; border-right: 2px solid #a19f9d !important; }
        .dark .sticky-col-2 { border-right-color: #4b5563 !important; }

        .excel-col-letter.sticky-col-0,
        .excel-col-letter.sticky-col-1,
        .excel-col-letter.sticky-col-2,
        .excel-field-header.sticky-col-0,
        .excel-field-header.sticky-col-1,
        .excel-field-header.sticky-col-2 {
            z-index: 30;
        }

        /* Nomor Baris */
        .excel-row-num {
            background-color: #f3f2f1;
            color: #484644;
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            user-select: none;
        }
        .dark .excel-row-num {
            background-color: #1f2937;
            color: #9ca3af;
        }
        .excel-row-num.is-active {
            background-color: #d2e8dc !important;
            color: #107c41 !important;
            border-right: 2px solid #107c41 !important;
        }
        .dark .excel-row-num.is-active {
            background-color: #064e3b !important;
            color: #34d399 !important;
        }

        /* Sel Info Siswa */
        .excel-info-cell {
            background-color: #ffffff;
            color: #201f1e;
            padding: 0 10px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 12.5px;
        }
        .dark .excel-info-cell {
            background-color: #111827;
            color: #e5e7eb;
        }
        .excel-row-hover:hover .excel-info-cell {
            background-color: #f8fafc;
        }
        .dark .excel-row-hover:hover .excel-info-cell {
            background-color: #1e293b;
        }

        /* Sel Input Nilai & Select Ketercapaian */
        .excel-cell-td {
            position: relative;
            background-color: #ffffff;
            width: 170px;
            min-width: 150px;
        }
        .dark .excel-cell-td {
            background-color: #0f172a;
        }
        .excel-cell-input {
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background: transparent;
            text-align: right;
            padding: 0 10px;
            font-size: 13px;
            font-variant-numeric: tabular-nums;
            color: #111827;
            box-shadow: none !important;
        }
        .dark .excel-cell-input {
            color: #f9fafb;
        }
        .excel-cell-input:focus {
            background-color: #f0fdf4;
            box-shadow: inset 0 0 0 2px #107c41 !important;
            position: relative;
            z-index: 10;
        }
        .dark .excel-cell-input:focus {
            background-color: #052e16;
            box-shadow: inset 0 0 0 2px #34d399 !important;
        }
        .excel-cell-invalid {
            background-color: #fef2f2 !important;
            color: #dc2626 !important;
            box-shadow: inset 0 0 0 2px #dc2626 !important;
        }

        .excel-cell-select {
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background: transparent;
            padding: 0 8px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: none !important;
        }
        .excel-cell-select:focus {
            box-shadow: inset 0 0 0 2px #107c41 !important;
            position: relative;
            z-index: 10;
        }
        .dark .excel-cell-select:focus {
            box-shadow: inset 0 0 0 2px #34d399 !important;
        }
        .status-achieved {
            color: #15803d;
            background-color: #f0fdf4;
        }
        .dark .status-achieved {
            color: #4ade80;
            background-color: rgba(20, 83, 45, 0.25);
        }
        .status-not-achieved {
            color: #b91c1c;
            background-color: #fef2f2;
        }
        .dark .status-not-achieved {
            color: #f87171;
            background-color: rgba(127, 29, 29, 0.25);
        }

        /* Footer Statistik */
        .excel-stat-cell {
            background-color: #f8fafc;
            font-weight: 600;
            text-align: right;
            padding: 0 10px !important;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
        }
        .dark .excel-stat-cell {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        .excel-summary-row td {
            background-color: #f3f2f1 !important;
            font-weight: 700;
            font-size: 12px;
            color: #201f1e;
            border-top: 2px solid #a19f9d;
        }
        .dark .excel-summary-row td {
            background-color: #1f2937 !important;
            color: #f3f4f6;
            border-top-color: #4b5563;
        }

        /* Bottom Bar */
        .excel-bottom-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px;
            background-color: #f3f2f1;
            border-top: 1px solid #d2d0ce;
            font-size: 12px;
            color: #605e5c;
        }
        .dark .excel-bottom-bar {
            background-color: #1f2937;
            border-color: #374151;
            color: #9ca3af;
        }
        .excel-sheet-tab {
            background-color: #ffffff;
            color: #107c41;
            font-weight: 700;
            padding: 3px 12px;
            border-bottom: 2px solid #107c41;
            border-radius: 4px 4px 0 0;
        }
        .dark .excel-sheet-tab {
            background-color: #111827;
            color: #34d399;
            border-bottom-color: #34d399;
        }
    </style>

    {{-- Form Filter Parameter --}}
    {{ $this->form }}

    @if(!empty($this->data['class_id']) && !empty($this->data['subject_id']) && !empty($this->data['learning_objective_id']))
        <div
            wire:key="excel-formatif-{{ $this->data['class_id'] }}-{{ $this->data['subject_id'] }}-{{ $this->data['learning_objective_id'] }}-{{ $this->data['type'] ?? '' }}"
            x-data="excelFormatifSheet({
                initialStudents: @js($this->data['students'] ?? []),
            })"
            x-on:keydown.window.ctrl.s.prevent="saveToServer()"
            x-on:keydown.window.meta.s.prevent="saveToServer()"
            class="excel-container"
        >
            {{-- Ribbon Hijau Excel --}}
            <div class="excel-ribbon">
                <div class="excel-ribbon-title">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1.8 18H14l-2-3.4-2 3.4H8.2l2.9-4.5L8.4 11h1.8l1.8 3.1 1.8-3.1h1.8l-2.7 4.5 2.9 4.5zM13 9V3.5L18.5 9H13z"/>
                    </svg>
                    <span>Buku Nilai Formatif.xlsx</span>
                    <span x-show="statusMessage" x-text="statusMessage" class="excel-badge-status"></span>
                </div>

                <div class="excel-btn-group">
                    {{-- Alat Bantu KKTP Otomatis --}}
                    <div class="excel-kktp-box" x-show="students.length > 0">
                        <span>KKTP:</span>
                        <input type="number" min="0" max="100" x-model.number="kktp" class="excel-kktp-input" />
                        <button type="button" x-on:click="applyKktp()" class="excel-btn-secondary" style="padding: 3px 8px;">
                            Terapkan KKTP
                        </button>
                    </div>

                    <button
                        type="button"
                        x-on:click="clearAllScores()"
                        x-show="students.length > 0"
                        class="excel-btn-secondary"
                    >
                        Kosongkan Sel
                    </button>

                    <button
                        type="button"
                        x-on:click="saveToServer()"
                        x-bind:disabled="isSaving || students.length === 0"
                        class="excel-btn-save"
                    >
                        <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Nilai Formatif (Ctrl+S)'"></span>
                    </button>
                </div>
            </div>

            {{-- Formula Bar --}}
            <div class="excel-formula-bar">
                <div class="excel-name-box" x-text="activeCellCoordinate()"></div>
                <div class="excel-fx-label">fx</div>
                <div class="excel-formula-content">
                    <template x-if="activeRow !== null && activeCol !== null && students[activeRow]">
                        <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                            <span style="font-size: 12px; color: #605e5c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 50%;"
                                  x-text="students[activeRow].student_name + ' — ' + (activeCol === 0 ? 'Nilai (0-100)' : 'Ketercapaian') + ':'">
                            </span>
                            <template x-if="activeCol === 0">
                                <input
                                    type="text"
                                    class="excel-formula-input"
                                    x-model="students[activeRow].score"
                                    placeholder="Ketik nilai 0-100..."
                                />
                            </template>
                            <template x-if="activeCol === 1">
                                <span style="font-family: monospace; font-size: 12px; font-weight: 700;"
                                      x-text="students[activeRow].is_achieved === '1' ? 'Tercapai (Achieved)' : 'Belum Tercapai'">
                                </span>
                            </template>
                        </div>
                    </template>
                    <template x-if="activeRow === null || activeCol === null">
                        <span style="font-size: 12px; color: #8a8886; font-style: italic;">
                            Klik sel nilai untuk mengetik, gunakan tombol panah / Enter / Tab, atau tempel (Ctrl+V) langsung dari Excel...
                        </span>
                    </template>
                </div>
            </div>

            {{-- Grid Spreadsheet --}}
            <template x-if="students.length > 0">
                <div class="excel-scroll-area">
                    <table class="excel-table">
                        <thead>
                            {{-- Huruf Kolom Excel (A, B, C, D) --}}
                            <tr>
                                <th class="excel-col-letter sticky-col-0"></th>
                                <th class="excel-col-letter sticky-col-1">A</th>
                                <th class="excel-col-letter sticky-col-2">B</th>
                                <th class="excel-col-letter" :class="{ 'is-active': activeCol === 0 }">C</th>
                                <th class="excel-col-letter" :class="{ 'is-active': activeCol === 1 }">D</th>
                            </tr>

                            {{-- Judul Kolom --}}
                            <tr>
                                <th class="excel-field-header sticky-col-0" style="text-align: center;">No</th>
                                <th class="excel-field-header sticky-col-1">NIS</th>
                                <th class="excel-field-header sticky-col-2">Nama Siswa</th>
                                <th class="excel-field-header" style="text-align: right;">Nilai (0-100)</th>
                                <th class="excel-field-header" style="text-align: left;">Ketercapaian (1=Ya, 0=Belum)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="(student, rIdx) in students" :key="student.enrollment_id">
                                <tr class="excel-row-hover">
                                    {{-- Nomor Baris --}}
                                    <td
                                        class="excel-row-num sticky-col-0"
                                        :class="{ 'is-active': activeRow === rIdx }"
                                        x-text="rIdx + 1"
                                    ></td>

                                    {{-- Kolom A: NIS --}}
                                    <td class="excel-info-cell sticky-col-1" style="font-family: monospace;" x-text="student.nis"></td>

                                    {{-- Kolom B: Nama Siswa --}}
                                    <td class="excel-info-cell sticky-col-2" style="font-weight: 600;" :title="student.student_name" x-text="student.student_name"></td>

                                    {{-- Kolom C: Nilai (0-100) --}}
                                    <td class="excel-cell-td">
                                        <input
                                            type="text"
                                            inputmode="decimal"
                                            :id="'fmt-cell-' + rIdx + '-0'"
                                            x-model="student.score"
                                            x-on:focus="setActiveCell(rIdx, 0, $event)"
                                            x-on:keydown="handleInputKeydown($event, rIdx)"
                                            x-on:paste="handlePaste($event, rIdx, 0)"
                                            x-on:blur="sanitizeScore(rIdx)"
                                            :class="{ 'excel-cell-invalid': isInvalidScore(student.score) }"
                                            class="excel-cell-input"
                                            placeholder="-"
                                            autocomplete="off"
                                        />
                                    </td>

                                    {{-- Kolom D: Ketercapaian --}}
                                    <td class="excel-cell-td" style="width: 210px;">
                                        <select
                                            :id="'fmt-cell-' + rIdx + '-1'"
                                            x-model="student.is_achieved"
                                            x-on:focus="setActiveCell(rIdx, 1, null)"
                                            x-on:keydown="handleSelectKeydown($event, rIdx)"
                                            :class="student.is_achieved === '1' ? 'status-achieved' : 'status-not-achieved'"
                                            class="excel-cell-select"
                                        >
                                            <option value="1">Tercapai (Achieved)</option>
                                            <option value="0">Belum Tercapai</option>
                                        </select>
                                    </td>
                                </tr>
                            </template>
                        </tbody>

                        {{-- Baris Statistik Bawah --}}
                        <tfoot>
                            <tr class="excel-summary-row">
                                <td class="sticky-col-0" style="text-align: center; font-size: 10px;">fx</td>
                                <td class="sticky-col-1" style="padding: 0 10px;">AVERAGE</td>
                                <td class="sticky-col-2" style="padding: 0 10px;">Rata-Rata & Ketuntasan Kelas</td>
                                <td style="text-align: right; padding: 0 10px; color: #107c41;" x-text="classAverage()"></td>
                                <td style="padding: 0 10px; color: #107c41;" x-text="achievedSummary()"></td>
                            </tr>
                            <tr>
                                <td class="excel-row-num sticky-col-0" style="font-size: 10px;">MAX</td>
                                <td class="excel-info-cell sticky-col-1" style="font-weight: 600;">MAX</td>
                                <td class="excel-info-cell sticky-col-2" style="font-weight: 600;">Nilai Tertinggi</td>
                                <td class="excel-stat-cell" x-text="classMax()"></td>
                                <td class="excel-info-cell" style="color: #6b7280; font-size: 12px;">-</td>
                            </tr>
                            <tr>
                                <td class="excel-row-num sticky-col-0" style="font-size: 10px;">MIN</td>
                                <td class="excel-info-cell sticky-col-1" style="font-weight: 600;">MIN</td>
                                <td class="excel-info-cell sticky-col-2" style="font-weight: 600;">Nilai Terendah</td>
                                <td class="excel-stat-cell" x-text="classMin()"></td>
                                <td class="excel-info-cell" style="color: #6b7280; font-size: 12px;">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </template>

            {{-- State Kosong --}}
            <template x-if="students.length === 0">
                <div style="padding: 36px 16px; text-align: center; color: #6b7280; font-size: 13px;">
                    Tidak ada data siswa pada kelas yang dipilih.
                </div>
            </template>

            {{-- Status Bar & Sheet Tab Bawah --}}
            <div class="excel-bottom-bar">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="excel-sheet-tab">Sheet1 (Formatif per TP)</span>
                    <span x-text="students.length + ' Siswa • Terisi: ' + filledCount() + ' Nilai'"></span>
                </div>
                <div style="font-weight: 600;">READY</div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('excelFormatifSheet', ({ initialStudents }) => ({
                students: JSON.parse(JSON.stringify(initialStudents || [])),
                activeRow: null,
                activeCol: null,
                kktp: 75,
                isSaving: false,
                statusMessage: '',

                activeCellCoordinate() {
                    if (this.activeRow === null || this.activeCol === null) return 'C1';
                    const colLetter = this.activeCol === 0 ? 'C' : 'D';
                    return colLetter + (this.activeRow + 1);
                },

                setActiveCell(rIdx, cIdx, event) {
                    this.activeRow = rIdx;
                    this.activeCol = cIdx;
                    if (event && event.target && typeof event.target.select === 'function') {
                        event.target.select();
                    }
                },

                focusCell(rIdx, cIdx) {
                    if (rIdx < 0 || rIdx >= this.students.length) return;
                    if (cIdx < 0 || cIdx > 1) return;

                    this.$nextTick(() => {
                        const el = document.getElementById(`fmt-cell-${rIdx}-${cIdx}`);
                        if (el) {
                            el.focus();
                            if (typeof el.select === 'function') {
                                el.select();
                            }
                        }
                    });
                },

                handleInputKeydown(e, rIdx) {
                    const input = e.target;
                    const valStr = String(input.value ?? '');
                    const allSelected = input.selectionStart === 0 && input.selectionEnd === valStr.length;

                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.focusCell(rIdx - 1, 0);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.focusCell(rIdx + 1, 0);
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        this.focusCell(e.shiftKey ? rIdx - 1 : rIdx + 1, 0);
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        if (e.shiftKey) {
                            if (rIdx > 0) this.focusCell(rIdx - 1, 1);
                        } else {
                            this.focusCell(rIdx, 1);
                        }
                    } else if (e.key === 'ArrowRight') {
                        if (allSelected || input.selectionStart === valStr.length) {
                            e.preventDefault();
                            this.focusCell(rIdx, 1);
                        }
                    }
                },

                handleSelectKeydown(e, rIdx) {
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.focusCell(rIdx - 1, 1);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.focusCell(rIdx + 1, 1);
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        this.focusCell(e.shiftKey ? rIdx - 1 : rIdx + 1, 1);
                    } else if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        this.focusCell(rIdx, 0);
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        if (e.shiftKey) {
                            this.focusCell(rIdx, 0);
                        } else if (rIdx < this.students.length - 1) {
                            this.focusCell(rIdx + 1, 0);
                        }
                    } else if (e.key === '1' || e.key.toLowerCase() === 't' || e.key.toLowerCase() === 'y') {
                        e.preventDefault();
                        this.students[rIdx].is_achieved = '1';
                    } else if (e.key === '0' || e.key.toLowerCase() === 'b' || e.key.toLowerCase() === 'n') {
                        e.preventDefault();
                        this.students[rIdx].is_achieved = '0';
                    }
                },

                handlePaste(e, startRow, startCol) {
                    const clipboard = e.clipboardData || window.clipboardData;
                    const rawText = clipboard.getData('text');
                    if (!rawText) return;

                    if (rawText.includes('\t') || rawText.includes('\n')) {
                        e.preventDefault();

                        const rows = rawText
                            .replace(/\r\n/g, '\n')
                            .replace(/\n$/, '')
                            .split('\n');

                        let pastedCount = 0;

                        rows.forEach((rowStr, rOffset) => {
                            const targetRow = startRow + rOffset;
                            if (targetRow >= this.students.length) return;

                            const cols = rowStr.split('\t');
                            cols.forEach((cellVal, cOffset) => {
                                const targetCol = startCol + cOffset;
                                const cleaned = cellVal.trim();

                                if (targetCol === 0) {
                                    const numStr = cleaned.replace(',', '.');
                                    if (numStr === '' || numStr === '-') {
                                        this.students[targetRow].score = null;
                                    } else if (!isNaN(numStr)) {
                                        this.students[targetRow].score = parseFloat(numStr);
                                        pastedCount++;
                                    }
                                } else if (targetCol === 1) {
                                    const lower = cleaned.toLowerCase();
                                    if (['1', 'tercapai', 'ya', 'tuntas', 'achieved'].includes(lower)) {
                                        this.students[targetRow].is_achieved = '1';
                                    } else if (['0', 'belum', 'belum tercapai', 'tidak', 'belum tuntas'].includes(lower)) {
                                        this.students[targetRow].is_achieved = '0';
                                    }
                                }
                            });
                        });

                        this.flashStatus(`✓ ${pastedCount} nilai ditempel dari Excel`);
                    }
                },

                sanitizeScore(rIdx) {
                    let val = this.students[rIdx].score;
                    if (val === '' || val === null || val === undefined) {
                        this.students[rIdx].score = null;
                        return;
                    }
                    const normalized = String(val).trim().replace(',', '.');
                    if (!isNaN(normalized)) {
                        this.students[rIdx].score = parseFloat(normalized);
                    }
                },

                isInvalidScore(val) {
                    if (val === '' || val === null || val === undefined) return false;
                    const num = Number(String(val).replace(',', '.'));
                    return isNaN(num) || num < 0 || num > 100;
                },

                validScores() {
                    return this.students
                        .map(st => st.score)
                        .filter(v => v !== null && v !== '' && v !== undefined && !isNaN(Number(v)))
                        .map(v => Number(v));
                },

                filledCount() {
                    return this.validScores().length;
                },

                classAverage() {
                    const vals = this.validScores();
                    if (vals.length === 0) return '-';
                    return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
                },

                classMax() {
                    const vals = this.validScores();
                    return vals.length ? Math.max(...vals) : '-';
                },

                classMin() {
                    const vals = this.validScores();
                    return vals.length ? Math.min(...vals) : '-';
                },

                achievedSummary() {
                    if (this.students.length === 0) return '-';
                    const achievedCount = this.students.filter(st => st.is_achieved === '1').length;
                    const pct = Math.round((achievedCount / this.students.length) * 100);
                    return `${achievedCount} / ${this.students.length} Tercapai (${pct}%)`;
                },

                applyKktp() {
                    const threshold = Number(this.kktp);
                    if (isNaN(threshold) || threshold < 0 || threshold > 100) {
                        alert('Masukkan nilai KKTP antara 0 - 100.');
                        return;
                    }
                    let updated = 0;
                    this.students.forEach(st => {
                        if (st.score !== null && st.score !== '' && !isNaN(Number(st.score))) {
                            st.is_achieved = Number(st.score) >= threshold ? '1' : '0';
                            updated++;
                        }
                    });
                    this.flashStatus(`✓ KKTP ${threshold} diterapkan ke ${updated} siswa`);
                },

                clearAllScores() {
                    if (!confirm('Kosongkan seluruh sel nilai pada tabel ini?')) return;
                    this.students.forEach(st => {
                        st.score = null;
                        st.is_achieved = '1';
                    });
                    this.flashStatus('Seluruh sel dikosongkan');
                },

                flashStatus(msg) {
                    this.statusMessage = msg;
                    setTimeout(() => {
                        this.statusMessage = '';
                    }, 3000);
                },

                async saveToServer() {
                    for (const st of this.students) {
                        if (this.isInvalidScore(st.score)) {
                            alert(`Nilai tidak valid pada "${st.student_name}". Pastikan nilai berupa angka 0 - 100.`);
                            return;
                        }
                    }

                    this.isSaving = true;
                    try {
                        await this.$wire.set('data.students', this.students);
                        await this.$wire.save();
                        this.flashStatus('✓ Tersimpan ke database');
                    } finally {
                        this.isSaving = false;
                    }
                }
            }));
        });
    </script>
</x-filament-panels::page>