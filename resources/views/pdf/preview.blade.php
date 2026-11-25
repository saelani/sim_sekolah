<?php /** @var \Illuminate\View\ViewContext $view */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Laporan - SIM Sekolah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            background: #2c3e50;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; color: #bdc3c7; }
        .filters {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .filters .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        .filters .row > div { flex: 1 1 200px; }
        .filters label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        .filters input, .filters select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .filters .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filters .btn-group .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .filters .btn-primary {
            background: #3498db;
            color: #fff;
        }
        .filters .btn-primary:hover { background: #2980b9; }
        .filters .btn-outline {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
        }
        .filters .btn-outline:hover { background: #f8f9fa; }
        .pdf-viewer {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .pdf-viewer iframe {
            width: 100%;
            height: 800px;
            border: none;
        }
        .pdf-viewer .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 800px;
            color: #999;
        }
        .pdf-viewer .empty-state svg { width: 64px; height: 64px; margin-bottom: 15px; }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .actions .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .actions .btn-danger {
            background: #e74c3c;
            color: #fff;
        }
        .actions .btn-danger:hover { background: #c0392b; }
        .actions .btn-success {
            background: #27ae60;
            color: #fff;
        }
        .actions .btn-success:hover { background: #219a52; }
        .actions .btn-outline {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
        }
        .actions .btn-outline:hover { background: #f8f9fa; }

        @media (max-width: 768px) {
            .filters .row { flex-direction: column; }
            .pdf-viewer iframe { height: 600px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📄 Preview Laporan</h1>
        <p>Pilih jenis laporan dan filter untuk melihat tampilan PDF sebelum mencetak</p>
    </div>

    <div class="filters">
        <form id="filter-form">
            <div class="row">
                <div>
                    <label for="report_type">Jenis Laporan</label>
                    <select id="report_type" class="form-control">
                        <option value="">-- Pilih Laporan --</option>
                        <option value="student-age">Usia Siswa Bulanan</option>
                        <option value="student-age-yearly">Usia Siswa Tahunan</option>
                        <option value="student-attendance">Kehadiran Siswa (Ringkasan)</option>
                        <option value="student-attendance-matrix">Kehadiran Siswa (Matriks)</option>
                        <option value="student-saving">Tabungan Siswa</option>
                        <option value="learning-objective">Tujuan Pembelajaran</option>
                        <option value="schedule">Jadwal Pelajaran</option>
                        <option value="academic-calendar">Kalender Akademik</option>
                        <option value="academic-calendar-weekly">Kalender Akademik Mingguan</option>
                    </select>
                </div>
                <div>
                    <label for="class_id">Kelas</label>
                    <select id="class_id" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        // Load classes
                        $classes = \App\Models\SchoolClass::orderBy('name')->get();
                        foreach ($classes as $class) {
                            echo '<option value="' . e($class->id) . '">' . e($class->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="year">Tahun</label>
                    <input type="number" id="year" class="form-control" value="<?php echo e(now()->year); ?>" min="2024" max="2030">
                </div>
                <div>
                    <label for="month">Bulan</label>
                    <input type="number" id="month" class="form-control" value="<?php echo e(now()->month); ?>" min="1" max="12">
                </div>
                <div>
                    <label for="start_date">Tanggal Awal</label>
                    <input type="date" id="start_date" class="form-control">
                </div>
                <div>
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" id="end_date" class="form-control">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Tampilkan Preview</button>
            </div>
        </form>
    </div>

    <div class="pdf-viewer">
        <iframe id="pdf_frame" src="" title="Preview PDF" sandbox="allow-scripts allow-same-origin"></iframe>
        <div class="empty-state" id="empty_state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p>Pilih jenis laporan &kelas, lalu klik "Tampilkan Preview"</p>
        </div>
    </div>

    <div class="actions">
        <a id="print_btn" href="#" class="btn btn-outline" target="_blank">🖨️ Cetak</a>
        <a id="download_btn" href="#" class="btn btn-outline" download>⬇️ Unduh PDF</a>
        <a id="close_btn" href="#" class="btn btn-danger">✕ Tutup</a>
    </div>
</div>

<script>
    const reportTypes = {
        'student-age': {
            url: (params) => '/student-age/preview?class_id=' + params.class_id + '&month=' + params.month + '&year=' + params.year,
            label: 'Usia Siswa Bulanan'
        },
        'student-age-yearly': {
            url: (params) => '/student-age/yearly-preview?class_id=' + params.class_id + '&year=' + params.year,
            label: 'Usia Siswa Tahunan'
        },
        'student-attendance': {
            url: (params) => {
                const start = params.start_date || '';
                const end = params.end_date || '';
                return '/student-attendance/preview?class_id=' + params.class_id + '&start_date=' + start + '&end_date=' + end;
            },
            label: 'Kehadiran Siswa (Ringkasan)'
        },
        'student-saving': {
            url: (params) => {
                const start = params.start_date || '';
                const end = params.end_date || '';
                return '/student-saving/preview?class_id=' + params.class_id + '&start_date=' + start + '&end_date=' + end;
            },
            label: 'Tabungan Siswa'
        },
        'learning-objective': {
            url: (params) => '/learning-objective/preview?subject_id=' + params.subject_id + '&level=' + params.level + '&semester=' + params.semester,
            label: 'Tujuan Pembelajaran'
        },
        'schedule': {
            url: (params) => '/pdf/schedules/preview?class_id=' + params.class_id,
            label: 'Jadwal Pelajaran'
        },
        'academic-calendar': {
            url: (params) => '/academic-calendar/preview?class_id=' + params.class_id + '&semester=' + params.semester + '&year=' + params.year,
            label: 'Kalender Akademik'
        },
        'academic-calendar-weekly': {
            url: (params) => '/academic-calendar/weekly-preview?class_id=' + params.class_id + '&start_date=' + params.start_date + '&end_date=' + params.end_date,
            label: 'Kalender Akademik Mingguan'
        }
    };

    const iframe = document.getElementById('pdf_frame');
    const emptyState = document.getElementById('empty_state');
    const printBtn = document.getElementById('print_btn');
    const downloadBtn = document.getElementById('download_btn');
    const closeBtn = document.getElementById('close_btn');
    const form = document.getElementById('filter-form');
    const reportType = document.getElementById('report_type');
    const classSelect = document.getElementById('class_id');
    const yearInput = document.getElementById('year');
    const monthInput = document.getElementById('month');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const subjectInput = document.getElementById('subject_id');
    const levelInput = document.getElementById('level');
    const semesterInput = document.getElementById('semester');

    let currentParams = {};

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const type = reportType.value;
        if (!type) {
            alert('Silakan pilih jenis laporan terlebih dahulu.');
            return;
        }

        if (!classSelect.value) {
            alert('Silakan pilih kelas terlebih dahulu.');
            return;
        }

        const params = {
            class_id: classSelect.value,
            year: yearInput.value,
            month: monthInput.value,
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            subject_id: subjectInput.value,
            level: levelInput.value,
            semester: semesterInput.value
        };

        currentParams = params;
        const config = reportTypes[type];
        const url = config.url(params);
        iframe.src = url;
        emptyState.style.display = 'none';
    });

    // Close button
    closeBtn.addEventListener('click', () => {
        iframe.src = '';
        emptyState.style.display = 'flex';
    });

    // Print button
    printBtn.addEventListener('click', () => {
        if (iframe.src) {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    });

    // Download button
    downloadBtn.addEventListener('click', () => {
        if (iframe.src) {
            // For download, we need to change the URL to use a different endpoint
            // For now, just open the PDF in a new tab
            const type = reportType.value;
            const config = reportTypes[type];
            const url = config.url(currentParams);
            window.open(url, '_blank');
        }
    });
</script>
</body>
</html>
