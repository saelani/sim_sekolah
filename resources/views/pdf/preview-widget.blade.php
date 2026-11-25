<?php /** @var \App\Filament\Widgets\PdfPreviewWidget $widget */ ?>

<div class="p-4 space-y-4 bg-white rounded-lg shadow-sm border">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">📄 Preview Laporan</h3>
            <p class="text-xs text-gray-500 mt-1">
                Pilih jenis laporan & setel parameter, lalu klik "Tampilkan Preview"
            </p>
        </div>
        <div class="flex gap-2">
            <button
                id="preview_btn"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md transition"
            >
                👁️ Tampilkan Preview
            </button>
            <a
                id="download_btn"
                href="#"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-md transition"
            >
                ⬇️ Unduh
            </a>
            <button
                id="print_btn"
                class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-md transition"
            >
                🖨️ Cetak
            </button>
            <button
                id="close_btn"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-4 rounded-md transition"
            >
                ✕ Tutup
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Report Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
            <select
                id="report_type"
                wire:model="reportType"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="">-- Pilih Laporan --</option>
                <option value="student-age">Usia Siswa Bulanan</option>
                <option value="student-age-yearly">Usia Siswa Tahunan</option>
                <option value="student-attendance">Kehadiran Siswa (Ringkasan)</option>
                <option value="attendance-matrix">Kehadiran Siswa (Matriks)</option>
                <option value="student-saving">Tabungan Siswa</option>
                <option value="learning-objective">Tujuan Pembelajaran</option>
                <option value="schedule">Jadwal Pelajaran</option>
                <option value="academic-calendar">Kalender Akademik</option>
                <option value="academic-calendar-weekly">Kalender Akademik Mingguan</option>
            </select>
        </div>

        <!-- Class -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
            <select
                id="class_id"
                wire:model="classId"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="">-- Pilih Kelas --</option>
                @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Dynamic fields based on report type -->
    @if($reportType)
        <div class="space-y-3 pt-3 border-t border-gray-200">
            @if(in_array($reportType, ['student-age', 'attendance-matrix']))
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <input
                            id="year"
                            type="number"
                            wire:model="year"
                            min="2024"
                            max="{{ now()->year + 1 }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <input
                            id="month"
                            type="number"
                            wire:model="month"
                            min="1"
                            max="12"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>
            @elseif($reportType === 'student-age-yearly')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <input
                            id="year"
                            type="number"
                            wire:model="year"
                            min="2024"
                            max="{{ now()->year + 1 }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>
            @elseif(in_array($reportType, ['student-attendance', 'student-saving']))
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                        <input
                            id="start_date"
                            type="date"
                            wire:model="startDate"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input
                            id="end_date"
                            type="date"
                            wire:model="endDate"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>
            @elseif($reportType === 'academic-calendar')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select
                            id="semester"
                            wire:model="semester"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="">-- Pilih Semester --</option>
                            <option value="1">Semester 1 (Jul-Dec)</option>
                            <option value="2">Semester 2 (Jan-Jun)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <input
                            id="year"
                            type="number"
                            wire:model="year"
                            min="2024"
                            max="{{ now()->year + 1 }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>
            @elseif($reportType === 'academic-calendar-weekly')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                        <input
                            id="start_date"
                            type="date"
                            wire:model="startDate"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input
                            id="end_date"
                            type="date"
                            wire:model="endDate"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>
            @elseif($reportType === 'learning-objective')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mapel</label>
                        <select
                            id="subject_id"
                            wire:model="subjectId"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="">-- Pilih Mapel --</option>
                            @foreach(\App\Models\Subject::orderBy('name')->get() as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                        <select
                            id="level"
                            wire:model="level"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="">-- Pilih Level --</option>
                            @foreach(\App\Models\SchoolClass::orderBy('name')->get() as $class)
                                <option value="{{ $class->level }}">Kelas {{ $class->level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Preview Container -->
    <div class="border border-gray-300 rounded-lg overflow-hidden bg-gray-50">
        <div class="aspect-w-16 aspect-h-9 bg-white">
            <iframe
                id="pdf_frame"
                src="#"
                class="w-full h-full"
                title="Preview PDF"
                sandbox="allow-scripts allow-same-origin"
                style="min-height: 400px; display: none;"
            ></iframe>
            <div id="empty_state" class="flex flex-col items-center justify-center h-full text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm">Isi form di atas, lalu klik "Tampilkan Preview"</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', () => {
        const widget = window.livewire.find('@{{ $this->id }}');

        // Build and update preview URL on form change
        function updatePreview() {
            const params = new URLSearchParams();
            const type = document.getElementById('report_type').value;
            const classId = document.getElementById('class_id').value;

            if (!type) return;
            if (!classId) {
                alert('Silakan pilih kelas terlebih dahulu.');
                return;
            }

            params.append('class_id', classId);

            switch (type) {
                case 'student-age':
                    params.append('month', document.getElementById('month')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'student-age-yearly':
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'student-attendance':
                case 'student-saving':
                    params.append('start_date', document.getElementById('start_date')?.value || '');
                    params.append('end_date', document.getElementById('end_date')?.value || '');
                    break;
                case 'learning-objective':
                    params.append('subject_id', document.getElementById('subject_id')?.value || '');
                    params.append('level', document.getElementById('level')?.value || '');
                    params.append('semester', document.getElementById('semester')?.value || '');
                    break;
                case 'schedule':
                    break;
                case 'academic-calendar':
                    params.append('semester', document.getElementById('semester')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'academic-calendar-weekly':
                    params.append('start_date', document.getElementById('start_date')?.value || '');
                    params.append('end_date', document.getElementById('end_date')?.value || '');
                    break;
                case 'attendance-matrix':
                    params.append('month', document.getElementById('month')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
            }

            const url = '{{ route("student-age.preview") }}'.replace('student-age.preview', type + '.preview');
            // Build URL based on type
            let finalUrl = '';
            switch (type) {
                case 'student-age': finalUrl = '{{ route("student-age.preview") }}' + '?' + params.toString(); break;
                case 'student-age-yearly': finalUrl = '{{ route("student-age.yearly-preview") }}' + '?' + params.toString(); break;
                case 'student-attendance': finalUrl = '{{ route("student-attendance.preview") }}' + '?' + params.toString(); break;
                case 'student-saving': finalUrl = '{{ route("student-saving.preview") }}' + '?' + params.toString(); break;
                case 'learning-objective': finalUrl = '{{ route("learning-objective.preview") }}' + '?' + params.toString(); break;
                case 'schedule': finalUrl = '{{ route("pdf.schedules.preview") }}' + '?' + params.toString(); break;
                case 'academic-calendar': finalUrl = '{{ route("academic-calendar.preview") }}' + '?' + params.toString(); break;
                case 'academic-calendar-weekly': finalUrl = '{{ route("academic-calendar.weekly-preview") }}' + '?' + params.toString(); break;
                case 'attendance-matrix': finalUrl = '{{ route("student-age.preview") }}' + '?' + params.toString(); break;
            }

            const frame = document.getElementById('pdf_frame');
            frame.src = finalUrl;
            frame.style.display = 'block';
            document.getElementById('empty_state').style.display = 'none';
        }

        document.getElementById('report_type')?.addEventListener('change', updatePreview);
        document.getElementById('class_id')?.addEventListener('change', updatePreview);

        // Trigger initial update
        setTimeout(updatePreview, 100);

        // Print button
        document.getElementById('print_btn')?.addEventListener('click', () => {
            const frame = document.getElementById('pdf_frame');
            if (frame.src && frame.contentWindow) {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            }
        });

        // Download button
        document.getElementById('download_btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            const type = document.getElementById('report_type').value;
            const classId = document.getElementById('class_id').value;
            if (!type || !classId) {
                alert('Silakan pilih jenis laporan dan kelas terlebih dahulu.');
                return;
            }

            const params = new URLSearchParams();
            params.append('class_id', classId);

            switch (type) {
                case 'student-age':
                    params.append('month', document.getElementById('month')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'student-age-yearly':
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'student-attendance':
                case 'student-saving':
                    params.append('start_date', document.getElementById('start_date')?.value || '');
                    params.append('end_date', document.getElementById('end_date')?.value || '');
                    break;
                case 'learning-objective':
                    params.append('subject_id', document.getElementById('subject_id')?.value || '');
                    params.append('level', document.getElementById('level')?.value || '');
                    params.append('semester', document.getElementById('semester')?.value || '');
                    break;
                case 'schedule':
                    break;
                case 'academic-calendar':
                    params.append('semester', document.getElementById('semester')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
                case 'academic-calendar-weekly':
                    params.append('start_date', document.getElementById('start_date')?.value || '');
                    params.append('end_date', document.getElementById('end_date')?.value || '');
                    break;
                case 'attendance-matrix':
                    params.append('month', document.getElementById('month')?.value || '');
                    params.append('year', document.getElementById('year')?.value || '');
                    break;
            }

            let downloadUrl = '';
            switch (type) {
                case 'student-age': downloadUrl = '{{ route("student-age.preview") }}' + '?' + params.toString(); break;
                case 'student-age-yearly': downloadUrl = '{{ route("student-age.yearly-preview") }}' + '?' + params.toString(); break;
                case 'student-attendance': downloadUrl = '{{ route("student-attendance.preview") }}' + '?' + params.toString(); break;
                case 'student-saving': downloadUrl = '{{ route("student-saving.preview") }}' + '?' + params.toString(); break;
                case 'learning-objective': downloadUrl = '{{ route("learning-objective.preview") }}' + '?' + params.toString(); break;
                case 'schedule': downloadUrl = '{{ route("pdf.schedules.preview") }}' + '?' + params.toString(); break;
                case 'academic-calendar': downloadUrl = '{{ route("academic-calendar.preview") }}' + '?' + params.toString(); break;
                case 'academic-calendar-weekly': downloadUrl = '{{ route("academic-calendar.weekly-preview") }}' + '?' + params.toString(); break;
                case 'attendance-matrix': downloadUrl = '{{ route("student-age.preview") }}' + '?' + params.toString(); break;
            }

            window.open(downloadUrl, '_blank');
        });

        // Close button
        document.getElementById('close_btn')?.addEventListener('click', () => {
            document.getElementById('pdf_frame').src = '';
            document.getElementById('empty_state').style.display = 'flex';
        });
    });
</script>
