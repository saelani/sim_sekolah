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

        /* Toolbar Hijau Khas Excel */
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
            background-color: rgba(255, 255, 255, 0.2);
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
        .excel-btn-secondary {
            background-color: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 6px 12px;
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

        /* Formula Bar (Name Box + fx) */
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

        /* Area Scroll & Tabel Grid */
        .excel-scroll-area {
            max-height: 68vh;
            overflow: auto;
            position: relative;
        }
        .excel-table {
            border-collapse: separate;
            border-spacing: 0;
            width: max-content;
            min-width: 100%;
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

        /* Header Huruf Kolom (A, B, C, D...) */
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

        /* Header Judul Kolom */
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

        /* Sticky Kolom Kiri (No, NIS, Nama) */
        .sticky-col-0 { position: sticky; left: 0; z-index: 15; width: 46px; min-width: 46px; }
        .sticky-col-1 { position: sticky; left: 46px; z-index: 15; width: 110px; min-width: 110px; }
        .sticky-col-2 { position: sticky; left: 156px; z-index: 15; width: 240px; min-width: 240px; border-right: 2px solid #a19f9d !important; }
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

        /* Sel Info Siswa (Readonly) */
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

        /* Sel Input Nilai */
        .excel-cell-td {
            position: relative;
            background-color: #ffffff;
            min-width: 130px;
            width: 130px;
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

        /* Sel Rumus AVERAGE / MAX / MIN */
        .excel-avg-cell {
            background-color: #f8fafc;
            font-weight: 600;
            text-align: right;
            padding: 0 10px !important;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            min-width: 100px;
        }
        .dark .excel-avg-cell {
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

        /* Sheet Tab Bawah */
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

    {{-- Render form filter di atas --}}
    {{ $this->form }}

    @if(!empty($this->data['class_id']) && !empty($this->data['subject_id']) && !empty($this->data['phase']) && !empty($this->data['semester']))
        <div
            wire:key="excel-sheet-{{ $this->data['class_id'] }}-{{ $this->data['subject_id'] }}-{{ $this->data['phase'] }}-{{ $this->data['semester'] }}"
            x-data="excelSumatifSheet({
                initialStudents: @js($this->data['students'] ?? []),
                initialScopes: @js(collect($this->sumativeScopes)->map(fn($name, $id) => ['id' => (string)$id, 'name' => $name])->values()),
            })"
            x-on:keydown.window.ctrl.s.prevent="saveToServer()"
            x-on:keydown.window.meta.s.prevent="saveToServer()"
            class="excel-container"
        >
            {{-- Ribbon Hijau Atas --}}
            <div class="excel-ribbon">
                <div class="excel-ribbon-title">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1.8 18H14l-2-3.4-2 3.4H8.2l2.9-4.5L8.4 11h1.8l1.8 3.1 1.8-3.1h1.8l-2.7 4.5 2.9 4.5zM13 9V3.5L18.5 9H13z"/>
                    </svg>
                    <span>Buku Nilai Sumatif.xlsx</span>
                    <span x-show="statusMessage" x-text="statusMessage" class="excel-badge-status"></span>
                </div>

                <div class="excel-btn-group">
                    <span style="font-size: 11px; opacity: 0.9; margin-right: 6px;" class="hidden md:inline">
                        Panah / Enter / Tab • Paste dari Excel (Ctrl+V) • Simpan (Ctrl+S)
                    </span>

                    <button
                        type="button"
                        x-on:click="clearAllScores()"
                        x-show="students.length > 0 && scopes.length > 0"
                        class="excel-btn-secondary"
                    >
                        Kosongkan Sel
                    </button>

                    <button
                        type="button"
                        x-on:click="saveToServer()"
                        x-bind:disabled="isSaving || students.length === 0 || scopes.length === 0"
                        class="excel-btn-save"
                    >
                        <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Nilai Sumatif (Ctrl+S)'"></span>
                    </button>
                </div>
            </div>

            {{-- Formula Bar --}}
            <div class="excel-formula-bar">
                <div class="excel-name-box" x-text="activeCellCoordinate()"></div>
                <div class="excel-fx-label">fx</div>
                <div class="excel-formula-content">
                    <template x-if="activeRow !== null && activeCol !== null && students[activeRow] && scopes[activeCol]">
                        <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                            <span style="font-size: 12px; color: #605e5c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 45%;"
                                  x-text="students[activeRow].student_name + ' — ' + scopes[activeCol].name + ':'">
                            </span>
                            <input
                                type="text"
                                class="excel-formula-input"
                                x-model="students[activeRow].scores[scopes[activeCol].id]"
                                placeholder="Ketik nilai 0-100..."
                            />
                        </div>
                    </template>
                    <template x-if="activeRow === null || activeCol === null">
                        <span style="font-size: 12px; color: #8a8886; font-style: italic;">
                            Klik sel nilai untuk mengetik atau tempel (Ctrl+V) langsung dari file Excel...
                        </span>
                    </template>
                </div>
            </div>

            {{-- Tabel Spreadsheet --}}
            <template x-if="students.length > 0 && scopes.length > 0">
                <div class="excel-scroll-area">
                    <table class="excel-table">
                        <thead>
                            {{-- Huruf Kolom Excel (A, B, C, D...) --}}
                            <tr>
                                <th class="excel-col-letter sticky-col-0"></th>
                                <th class="excel-col-letter sticky-col-1">A</th>
                                <th class="excel-col-letter sticky-col-2">B</th>
                                <template x-for="(scope, cIdx) in scopes" :key="'col-letter-' + scope.id">
                                    <th
                                        class="excel-col-letter"
                                        :class="{ 'is-active': activeCol === cIdx }"
                                        x-text="colLetter(cIdx + 2)"
                                    ></th>
                                </template>
                                <th class="excel-col-letter" x-text="colLetter(scopes.length + 2)"></th>
                            </tr>

                            {{-- Nama Kolom --}}
                            <tr>
                                <th class="excel-field-header sticky-col-0" style="text-align: center;">No</th>
                                <th class="excel-field-header sticky-col-1">NIS</th>
                                <th class="excel-field-header sticky-col-2">Nama Siswa</th>
                                <template x-for="(scope, cIdx) in scopes" :key="'head-' + scope.id">
                                    <th class="excel-field-header" :title="scope.name" style="text-align: center;">
                                        <span x-text="scope.name"></span>
                                    </th>
                                </template>
                                <th class="excel-field-header" style="text-align: right;">AVERAGE</th>
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

                                    {{-- Kolom C, D, E...: Nilai Lingkup Materi --}}
                                    <template x-for="(scope, cIdx) in scopes" :key="student.enrollment_id + '-' + scope.id">
                                        <td class="excel-cell-td">
                                            <input
                                                type="text"
                                                inputmode="decimal"
                                                :id="'cell-' + rIdx + '-' + cIdx"
                                                x-model="student.scores[scope.id]"
                                                x-on:focus="setActiveCell(rIdx, cIdx, $event)"
                                                x-on:keydown="handleKeydown($event, rIdx, cIdx)"
                                                x-on:paste="handlePaste($event, rIdx, cIdx)"
                                                x-on:blur="sanitizeCell(rIdx, scope.id)"
                                                :class="{ 'excel-cell-invalid': isInvalidScore(student.scores[scope.id]) }"
                                                class="excel-cell-input"
                                                autocomplete="off"
                                            />
                                        </td>
                                    </template>

                                    {{-- Kolom Rata-rata per Siswa --}}
                                    <td class="excel-avg-cell" x-text="rowAverage(student)"></td>
                                </tr>
                            </template>
                        </tbody>

                        {{-- Baris Rumus Statistik Bawah --}}
                        <tfoot>
                            <tr class="excel-summary-row">
                                <td class="sticky-col-0" style="text-align: center; font-size: 10px;">fx</td>
                                <td class="sticky-col-1" style="padding: 0 10px;">AVERAGE</td>
                                <td class="sticky-col-2" style="padding: 0 10px;">Rata-Rata Kelas</td>
                                <template x-for="scope in scopes" :key="'avg-' + scope.id">
                                    <td style="text-align: right; padding: 0 10px;" x-text="colAverage(scope.id)"></td>
                                </template>
                                <td style="text-align: right; padding: 0 10px; color: #107c41;" x-text="overallAverage()"></td>
                            </tr>
                            <tr>
                                <td class="excel-row-num sticky-col-0" style="font-size: 10px;">MAX</td>
                                <td class="excel-info-cell sticky-col-1" style="font-weight: 600;">MAX</td>
                                <td class="excel-info-cell sticky-col-2" style="font-weight: 600;">Nilai Tertinggi</td>
                                <template x-for="scope in scopes" :key="'max-' + scope.id">
                                    <td class="excel-avg-cell" x-text="colMax(scope.id)"></td>
                                </template>
                                <td class="excel-avg-cell">-</td>
                            </tr>
                            <tr>
                                <td class="excel-row-num sticky-col-0" style="font-size: 10px;">MIN</td>
                                <td class="excel-info-cell sticky-col-1" style="font-weight: 600;">MIN</td>
                                <td class="excel-info-cell sticky-col-2" style="font-weight: 600;">Nilai Terendah</td>
                                <template x-for="scope in scopes" :key="'min-' + scope.id">
                                    <td class="excel-avg-cell" x-text="colMin(scope.id)"></td>
                                </template>
                                <td class="excel-avg-cell">-</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </template>

            {{-- State Jika Data Kosong --}}
            <template x-if="students.length === 0 || scopes.length === 0">
                <div style="padding: 36px 16px; text-align: center; color: #6b7280; font-size: 13px;">
                    Tidak ada data siswa atau lingkup materi belum dibuat untuk filter ini.
                </div>
            </template>

            {{-- Status Bar & Sheet Tab Bawah --}}
            <div class="excel-bottom-bar">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="excel-sheet-tab">Sheet1 (Nilai Sumatif)</span>
                    <span x-text="students.length + ' Siswa × ' + scopes.length + ' Lingkup Materi'"></span>
                </div>
                <div style="font-weight: 600;">READY</div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('excelSumatifSheet', ({ initialStudents, initialScopes }) => ({
                students: JSON.parse(JSON.stringify(initialStudents || [])),
                scopes: initialScopes || [],
                activeRow: null,
                activeCol: null,
                isSaving: false,
                statusMessage: '',

                colLetter(index) {
                    let letter = '';
                    let temp = index;
                    while (temp >= 0) {
                        letter = String.fromCharCode((temp % 26) + 65) + letter;
                        temp = Math.floor(temp / 26) - 1;
                    }
                    return letter;
                },

                activeCellCoordinate() {
                    if (this.activeRow === null || this.activeCol === null) return 'A1';
                    return this.colLetter(this.activeCol + 2) + (this.activeRow + 1);
                },

                setActiveCell(rIdx, cIdx, event) {
                    this.activeRow = rIdx;
                    this.activeCol = cIdx;
                    if (event && event.target) {
                        event.target.select();
                    }
                },

                focusCell(rIdx, cIdx) {
                    if (rIdx < 0 || rIdx >= this.students.length) return;
                    if (cIdx < 0 || cIdx >= this.scopes.length) return;

                    this.$nextTick(() => {
                        const el = document.getElementById(`cell-${rIdx}-${cIdx}`);
                        if (el) {
                            el.focus();
                            el.select();
                        }
                    });
                },

                handleKeydown(e, rIdx, cIdx) {
                    const input = e.target;
                    const valStr = String(input.value ?? '');
                    const allSelected = input.selectionStart === 0 && input.selectionEnd === valStr.length;

                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.focusCell(rIdx - 1, cIdx);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.focusCell(rIdx + 1, cIdx);
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        this.focusCell(e.shiftKey ? rIdx - 1 : rIdx + 1, cIdx);
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        if (e.shiftKey) {
                            if (cIdx > 0) this.focusCell(rIdx, cIdx - 1);
                            else if (rIdx > 0) this.focusCell(rIdx - 1, this.scopes.length - 1);
                        } else {
                            if (cIdx < this.scopes.length - 1) this.focusCell(rIdx, cIdx + 1);
                            else if (rIdx < this.students.length - 1) this.focusCell(rIdx + 1, 0);
                        }
                    } else if (e.key === 'ArrowLeft') {
                        if (allSelected || input.selectionStart === 0) {
                            e.preventDefault();
                            this.focusCell(rIdx, cIdx - 1);
                        }
                    } else if (e.key === 'ArrowRight') {
                        if (allSelected || input.selectionStart === valStr.length) {
                            e.preventDefault();
                            this.focusCell(rIdx, cIdx + 1);
                        }
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
                                if (targetCol >= this.scopes.length) return;

                                const scopeId = this.scopes[targetCol].id;
                                const cleaned = cellVal.trim().replace(',', '.');

                                if (cleaned === '' || cleaned === '-') {
                                    this.students[targetRow].scores[scopeId] = null;
                                } else if (!isNaN(cleaned)) {
                                    this.students[targetRow].scores[scopeId] = parseFloat(cleaned);
                                    pastedCount++;
                                }
                            });
                        });

                        this.flashStatus(`✓ ${pastedCount} sel ditempel dari Excel`);
                    }
                },

                sanitizeCell(rIdx, scopeId) {
                    let val = this.students[rIdx].scores[scopeId];
                    if (val === '' || val === null || val === undefined) {
                        this.students[rIdx].scores[scopeId] = null;
                        return;
                    }
                    const normalized = String(val).trim().replace(',', '.');
                    if (!isNaN(normalized)) {
                        this.students[rIdx].scores[scopeId] = parseFloat(normalized);
                    }
                },

                isInvalidScore(val) {
                    if (val === '' || val === null || val === undefined) return false;
                    const num = Number(String(val).replace(',', '.'));
                    return isNaN(num) || num < 0 || num > 100;
                },

                rowAverage(student) {
                    const vals = this.scopes
                        .map(s => student.scores[s.id])
                        .filter(v => v !== null && v !== '' && v !== undefined && !isNaN(Number(v)))
                        .map(v => Number(v));

                    if (vals.length === 0) return '-';
                    return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
                },

                colAverage(scopeId) {
                    const vals = this.students
                        .map(st => st.scores[scopeId])
                        .filter(v => v !== null && v !== '' && v !== undefined && !isNaN(Number(v)))
                        .map(v => Number(v));

                    if (vals.length === 0) return '-';
                    return (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1);
                },

                colMax(scopeId) {
                    const vals = this.students
                        .map(st => st.scores[scopeId])
                        .filter(v => v !== null && v !== '' && v !== undefined && !isNaN(Number(v)))
                        .map(v => Number(v));

                    return vals.length ? Math.max(...vals) : '-';
                },

                colMin(scopeId) {
                    const vals = this.students
                        .map(st => st.scores[scopeId])
                        .filter(v => v !== null && v !== '' && v !== undefined && !isNaN(Number(v)))
                        .map(v => Number(v));

                    return vals.length ? Math.min(...vals) : '-';
                },

                overallAverage() {
                    const avgs = this.students
                        .map(st => this.rowAverage(st))
                        .filter(v => v !== '-')
                        .map(v => Number(v));

                    if (avgs.length === 0) return '-';
                    return (avgs.reduce((a, b) => a + b, 0) / avgs.length).toFixed(1);
                },

                clearAllScores() {
                    if (!confirm('Kosongkan seluruh sel nilai pada tabel ini?')) return;
                    this.students.forEach(st => {
                        this.scopes.forEach(sc => {
                            st.scores[sc.id] = null;
                        });
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
                        for (const sc of this.scopes) {
                            if (this.isInvalidScore(st.scores[sc.id])) {
                                alert(`Nilai tidak valid pada "${st.student_name}" (${sc.name}). Masukkan angka 0 - 100.`);
                                return;
                            }
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