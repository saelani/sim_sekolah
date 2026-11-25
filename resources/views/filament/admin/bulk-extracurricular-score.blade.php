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

        /* Ribbon Hijau Excel */
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
        .excel-btn-secondary {
            background-color: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 5px 12px;
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
            height: 36px;
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
            height: 34px;
            box-sizing: border-box;
        }
        .dark .excel-table th,
        .dark .excel-table td {
            border-color: #374151;
        }

        /* Huruf Kolom (A, B, C, D) */
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

        /* Judul Kolom */
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
        .sticky-col-1 { position: sticky; left: 46px; z-index: 15; width: 115px; min-width: 115px; }
        .sticky-col-2 { position: sticky; left: 161px; z-index: 15; width: 230px; min-width: 230px; border-right: 2px solid #a19f9d !important; }
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

        /* Sel Input Predikat & Deskripsi */
        .excel-cell-td {
            position: relative;
            background-color: #ffffff;
        }
        .dark .excel-cell-td {
            background-color: #0f172a;
        }
        .excel-grade-select {
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background: transparent;
            padding: 0 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: none !important;
        }
        .excel-grade-select:focus {
            box-shadow: inset 0 0 0 2px #107c41 !important;
            position: relative;
            z-index: 10;
        }
        .dark .excel-grade-select:focus {
            box-shadow: inset 0 0 0 2px #34d399 !important;
        }

        .grade-a { color: #15803d; background-color: #f0fdf4; }
        .dark .grade-a { color: #4ade80; background-color: rgba(20, 83, 45, 0.25); }
        .grade-b { color: #0369a1; background-color: #f0f9ff; }
        .dark .grade-b { color: #38bdf8; background-color: rgba(12, 74, 110, 0.25); }
        .grade-c { color: #b45309; background-color: #fffbeb; }
        .dark .grade-c { color: #fbbf24; background-color: rgba(120, 53, 15, 0.25); }
        .grade-d { color: #b91c1c; background-color: #fef2f2; }
        .dark .grade-d { color: #f87171; background-color: rgba(127, 29, 29, 0.25); }

        .excel-desc-input {
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background: transparent;
            padding: 0 10px;
            font-size: 12.5px;
            color: #111827;
            box-shadow: none !important;
        }
        .dark .excel-desc-input {
            color: #f9fafb;
        }
        .excel-desc-input:focus {
            background-color: #f0fdf4;
            box-shadow: inset 0 0 0 2px #107c41 !important;
            position: relative;
            z-index: 10;
        }
        .dark .excel-desc-input:focus {
            background-color: #052e16;
            box-shadow: inset 0 0 0 2px #34d399 !important;
        }

        /* Footer Statistik */
        .excel-summary-row td {
            background-color: #f3f2f1 !important;
            font-weight: 700;
            font-size: 12px;
            color: #201f1e;
            border-top: 2px solid #a19f9d;
            padding: 0 10px !important;
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

        .excel-grade-select {
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background-color: transparent;
            color: #111827;
            color-scheme: light;
            padding: 0 8px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: none !important;
        }
        .dark .excel-grade-select {
            color: #f9fafb;
            color-scheme: dark;
        }
        .excel-grade-select option {
            background-color: #ffffff;
            color: #111827;
            font-weight: 600;
        }
        .dark .excel-grade-select option {
            background-color: #1f2937;
            color: #f9fafb;
        }
        .excel-grade-select:focus {
            box-shadow: inset 0 0 0 2px #107c41 !important;
            position: relative;
            z-index: 10;
        }
        .dark .excel-grade-select:focus {
            box-shadow: inset 0 0 0 2px #34d399 !important;
        }
    </style>

    {{-- Form Filter Parameter --}}
    {{ $this->form }}

    @if(!empty($this->data['class_id']) && !empty($this->data['grade_extracurricular_id']))
        <div
            wire:key="excel-ekskul-{{ $this->data['class_id'] }}-{{ $this->data['grade_extracurricular_id'] }}"
            x-data="excelEkskulSheet({
                initialStudents: @js($this->data['students'] ?? []),
                ekskulName: @js($this->extracurricularName),
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
                    <span>Nilai Ekstrakurikuler.xlsx</span>
                    <span x-show="statusMessage" x-text="statusMessage" class="excel-badge-status"></span>
                </div>

                <div class="excel-btn-group">
                    <button
                        type="button"
                        x-on:click="autoGenerateDescriptions()"
                        x-show="students.length > 0"
                        class="excel-btn-secondary"
                        title="Otomatis mengisi deskripsi berdasarkan predikat (A/B/C/D) yang sudah dipilih"
                    >
                        Isi Deskripsi Otomatis
                    </button>

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
                        <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Nilai Ekskul (Ctrl+S)'"></span>
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
                            <span style="font-size: 12px; color: #605e5c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 40%;"
                                  x-text="students[activeRow].student_name + ' — ' + (activeCol === 0 ? 'Predikat' : 'Deskripsi') + ':'">
                            </span>

                            <template x-if="activeCol === 0">
                                <span style="font-family: monospace; font-size: 12px; font-weight: 700;"
                                      x-text="gradeLabel(students[activeRow].grade)">
                                </span>
                            </template>

                            <template x-if="activeCol === 1">
                                <input
                                    type="text"
                                    class="excel-formula-input"
                                    x-model="students[activeRow].description"
                                    placeholder="Ketik deskripsi kegiatan atau catatan siswa..."
                                />
                            </template>
                        </div>
                    </template>
                    <template x-if="activeRow === null || activeCol === null">
                        <span style="font-size: 12px; color: #8a8886; font-style: italic;">
                            Klik sel Predikat (tekan A/B/C/D) atau sel Deskripsi, gunakan Panah / Enter / Tab, atau tempel (Ctrl+V) dari Excel...
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

                            {{-- Nama Kolom --}}
                            <tr>
                                <th class="excel-field-header sticky-col-0" style="text-align: center;">No</th>
                                <th class="excel-field-header sticky-col-1">NIS</th>
                                <th class="excel-field-header sticky-col-2">Nama Siswa</th>
                                <th class="excel-field-header" style="width: 190px; min-width: 190px;">Predikat (A/B/C/D)</th>
                                <th class="excel-field-header" style="min-width: 360px;">Deskripsi Kegiatan / Catatan</th>
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

                                    {{-- Kolom C: Predikat --}}
                                    <td class="excel-cell-td" style="width: 190px;">
                                        <select
                                            :id="'ekskul-cell-' + rIdx + '-0'"
                                            x-model="student.grade"
                                            x-on:focus="setActiveCell(rIdx, 0, null)"
                                            x-on:keydown="handleGradeKeydown($event, rIdx)"
                                            x-on:paste="handlePaste($event, rIdx, 0)"
                                            :class="gradeClass(student.grade)"
                                            class="excel-grade-select"
                                        >
                                            <option value="">-- Pilih Grade --</option>
                                            <option value="A">A (Sangat Baik)</option>
                                            <option value="B">B (Baik)</option>
                                            <option value="C">C (Cukup)</option>
                                            <option value="D">D (Kurang)</option>
                                        </select>
                                    </td>

                                    {{-- Kolom D: Deskripsi --}}
                                    <td class="excel-cell-td">
                                        <input
                                            type="text"
                                            :id="'ekskul-cell-' + rIdx + '-1'"
                                            x-model="student.description"
                                            x-on:focus="setActiveCell(rIdx, 1, $event)"
                                            x-on:keydown="handleDescKeydown($event, rIdx)"
                                            x-on:paste="handlePaste($event, rIdx, 1)"
                                            class="excel-desc-input"
                                            placeholder="Tulis deskripsi atau catatan..."
                                            autocomplete="off"
                                        />
                                    </td>
                                </tr>
                            </template>
                        </tbody>

                        {{-- Baris Ringkasan Statistik Bawah --}}
                        <tfoot>
                            <tr class="excel-summary-row">
                                <td class="sticky-col-0" style="text-align: center; font-size: 10px;">fx</td>
                                <td class="sticky-col-1">COUNTIF</td>
                                <td class="sticky-col-2">Rekapitulasi Predikat</td>
                                <td style="color: #107c41;" x-text="gradeDistribution()"></td>
                                <td style="color: #107c41;" x-text="'Terisi: ' + filledCount() + ' dari ' + students.length + ' Siswa'"></td>
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
                    <span class="excel-sheet-tab">Sheet1 (Ekstrakurikuler)</span>
                    <span x-text="students.length + ' Siswa • Terisi: ' + filledCount()"></span>
                </div>
                <div style="font-weight: 600;">READY</div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('excelEkskulSheet', ({ initialStudents, ekskulName }) => ({
                students: JSON.parse(JSON.stringify(initialStudents || [])).map(st => ({
                    ...st,
                    grade: st.grade ?? '',
                    description: st.description ?? '',
                })),
                ekskulName: ekskulName || 'ekstrakurikuler',
                activeRow: null,
                activeCol: null,
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
                        const el = document.getElementById(`ekskul-cell-${rIdx}-${cIdx}`);
                        if (el) {
                            el.focus();
                            if (typeof el.select === 'function') {
                                el.select();
                            }
                        }
                    });
                },

                handleGradeKeydown(e, rIdx) {
                    const key = e.key.toUpperCase();

                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this.focusCell(rIdx - 1, 0);
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.focusCell(rIdx + 1, 0);
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        this.focusCell(e.shiftKey ? rIdx - 1 : rIdx + 1, 0);
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        this.focusCell(rIdx, 1);
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        if (e.shiftKey) {
                            if (rIdx > 0) this.focusCell(rIdx - 1, 1);
                        } else {
                            this.focusCell(rIdx, 1);
                        }
                    } else if (['A', 'B', 'C', 'D'].includes(key) && !e.ctrlKey && !e.metaKey) {
                        e.preventDefault();
                        this.students[rIdx].grade = key;
                    } else if (e.key === 'Delete' || e.key === 'Backspace') {
                        e.preventDefault();
                        this.students[rIdx].grade = '';
                    }
                },

                handleDescKeydown(e, rIdx) {
                    const input = e.target;
                    const valStr = String(input.value ?? '');
                    const allSelected = input.selectionStart === 0 && input.selectionEnd === valStr.length;

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
                        if (allSelected || input.selectionStart === 0) {
                            e.preventDefault();
                            this.focusCell(rIdx, 0);
                        }
                    } else if (e.key === 'Tab') {
                        e.preventDefault();
                        if (e.shiftKey) {
                            this.focusCell(rIdx, 0);
                        } else if (rIdx < this.students.length - 1) {
                            this.focusCell(rIdx + 1, 0);
                        }
                    }
                },

                normalizeGrade(raw) {
                    const cleaned = String(raw ?? '').trim().toUpperCase();
                    if (['A', 'SANGAT BAIK'].includes(cleaned)) return 'A';
                    if (['B', 'BAIK'].includes(cleaned)) return 'B';
                    if (['C', 'CUKUP'].includes(cleaned)) return 'C';
                    if (['D', 'KURANG'].includes(cleaned)) return 'D';
                    return '';
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
                                    this.students[targetRow].grade = this.normalizeGrade(cleaned);
                                    pastedCount++;
                                } else if (targetCol === 1) {
                                    this.students[targetRow].description = cleaned === '-' ? '' : cleaned;
                                    pastedCount++;
                                }
                            });
                        });

                        this.flashStatus(`✓ ${pastedCount} sel ditempel dari Excel`);
                    }
                },

                gradeLabel(grade) {
                    const map = {
                        A: 'A (Sangat Baik)',
                        B: 'B (Baik)',
                        C: 'C (Cukup)',
                        D: 'D (Kurang)',
                    };
                    return map[grade] || '(Kosong)';
                },

                gradeClass(grade) {
                    const map = {
                        A: 'grade-a',
                        B: 'grade-b',
                        C: 'grade-c',
                        D: 'grade-d',
                    };
                    return map[grade] || '';
                },

                filledCount() {
                    return this.students.filter(st => (st.grade && st.grade !== '') || (st.description && st.description.trim() !== '')).length;
                },

                gradeDistribution() {
                    const counts = { A: 0, B: 0, C: 0, D: 0 };
                    this.students.forEach(st => {
                        if (counts[st.grade] !== undefined) {
                            counts[st.grade]++;
                        }
                    });
                    return `A:${counts.A} | B:${counts.B} | C:${counts.C} | D:${counts.D}`;
                },

                autoGenerateDescriptions() {
                    const name = this.ekskulName || 'ekstrakurikuler';
                    const templates = {
                        A: `Sangat aktif, disiplin, dan menunjukkan prestasi yang sangat baik dalam kegiatan ${name}.`,
                        B: `Aktif dan menunjukkan perkembangan yang baik dalam mengikuti kegiatan ${name}.`,
                        C: `Cukup aktif dalam mengikuti kegiatan ${name}, namun perlu meningkatkan kedisiplinan.`,
                        D: `Kurang aktif dalam mengikuti kegiatan ${name} dan memerlukan pembinaan lebih lanjut.`,
                    };

                    let updated = 0;
                    this.students.forEach(st => {
                        if (st.grade && templates[st.grade]) {
                            st.description = templates[st.grade];
                            updated++;
                        }
                    });

                    if (updated === 0) {
                        alert('Pilih predikat (A/B/C/D) minimal pada 1 siswa terlebih dahulu.');
                        return;
                    }

                    this.flashStatus(`✓ Deskripsi otomatis diisi untuk ${updated} siswa`);
                },

                clearAllScores() {
                    if (!confirm('Kosongkan seluruh predikat dan deskripsi pada tabel ini?')) return;
                    this.students.forEach(st => {
                        st.grade = '';
                        st.description = '';
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