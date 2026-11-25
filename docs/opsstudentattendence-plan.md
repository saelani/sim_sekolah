# Plan: Bulk Student Attendance Input (`opsstudentattendence`)

## Scope
Bagian Operasional module: halaman input **massal** kehadiran siswa (bulk) — mirip pola `BulkStudentSaving`, dengan filter per guru/kelas, tanggal, dan status kehadiran (Hadir/Sakit/Izin/Alpa).

## Files to Create
1. `app/Filament/Admin/Resources/Operasional/Pages/BulkOpsStudentAttendance.php` — halaman input massal kehadiran siswa.
2. `docs/opsstudentattendence-plan.md` — dokumen plan ini.

## Files to Modify
1. `app/Filament/Admin/Resources/Operasional/OpsStudentAttendanceResource.php`
   - Tambahkan halaman `'bulk'` → `BulkOpsStudentAttendance::route('/bulk')`.
2. `app/Filament/Admin/Resources/Operasional/Pages/ListOpsStudentAttendances.php`
   - Tambahkan header action `'bulk'` mengarahkan ke `OpsStudentAttendanceResource::getUrl('bulk')`.
3. `app/Filament/Admin/Resources/Operasional/Schemas/OpsStudentAttendanceForm.php`
   - Opsional: reuse agar form bulk oles massal. (Status statusSwipe, tanggal, catatan opsional.)

## Database
Table `ops_student_attendances` (migration `2024_01_01_000037_create_ops_student_attendances_table`) —
`id`, `class_id`, `teacher_id` (nullable), `student_id`, `user_id` (nullable),
`date`, `status` (enum: Hadir/Sakit/Izin/Alpa, default 'Hadir'), `notes` (255, nullable),
`attachment_path` (nullable), `approval_status`, `approved_by`, `approved_at`, `timestamps`.
Unique: (`student_id`, `date`).

## Akses (Access Control)
- Admin / Headmaster / Super Admin → tampilkan **semua** siswa.
- Guru (teacher) → hanya siswa kelas wali kelas + kelas mapel yang mengajar (baca:
  `acad_classes.teacher_id`, `acad_teacher_subject_classes.class_id`).
  - `Student` table: `acad_students` (id, user_id, class_id, nis, name, ...).
  - `Teacher` table: `acad_teachers` (id, user_id, role_type, ...).
  - `SchoolClass` table: `acad_classes` (id, teacher_id, ...).

## Konsep Halaman
- **Judul halaman:** Input Kehadiran Massal Siswa
- **Atribut pre-fill:**
  - `date` = `now()->format('Y-m-d')`
  - `status` = `'Hadir'` (default)
- **Form:**
  - Section: `Parameter Kehadiran`: `date` (DatePicker), `status` (Select: Hadir/Sakit/Izin/Alpa).
  - Section: `Daftar Seluruh Siswa`: `Repeater` (`students`):
    - `student_id` (Hidden), `student_name` (Text, read-only), `nis` (Text, read-only)
    - `status` (Select: Hadir/Sakit/Izin/Alpa) — *user pilih status per siswa*
    - `notes` (Textarea, opsional)
- **Save:**
  - Looping `students`, insert `OpsStudentAttendance` hanya untuk siswa yang `status` !== null.
  - Notifikasi sukses / info jika nol yang disimpan.
  - Redirect ke index.

## Statistik Kehadiran
- `Hadir` = 0, `Sakit` = 1, `Izin` = 2, `Alpa` = 3 (bobot).
- Persentase kehadiran = `Hadir / (Hadir + Sakit + Izin + Alpa) * 100`.

## Preview
- View blade: `resources/views/filament/admin/resources/student-saving-resource/pages/bulk-student-saving.blade.php` (mirip, adapt).
