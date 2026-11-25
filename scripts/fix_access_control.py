#!/usr/bin/env python3
import os

base = '/home/saelani/lara/sim_sekolah'

def edit(path, old, new, replace_all=False, label=''):
    full = os.path.join(base, path)
    with open(full, 'r', encoding='utf-8') as f:
        c = f.read()
    n = c.count(old)
    if n == 0:
        print(f"  [WARN] 0-hits: {path} :: {label or old[:60]!r}")
        return
    c = c.replace(old, new)
    print(f"  [OK] {n}x : {path} :: {label or old[:60]!r}")
    with open(full, 'w', encoding='utf-8') as f:
        f.write(c)

# ---------- 1. Clear bugs ----------
# 1.1 StudentSavingsTable: lowercase import
edit('app/Filament/Admin/Resources/StudentSavings/Tables/StudentSavingsTable.php',
     'use App\\Models\\schoolClass;', 'use App\\Models\\SchoolClass;')

# 1.2 StudentSavingResource: [0] fallback filter
edit('app/Filament/Admin/Resources/StudentSavings/StudentSavingResource.php',
     "                $q->whereIn('class_id', empty($allowedClassIds) ? [0] : $allowedClassIds);",
     "                if (! empty($allowedClassIds)) {\n                    $q->whereIn('class_id', $allowedClassIds);\n                }")

# 1.3 StudentAuthController: remove password from log
edit('app/Http/Controllers/StudentAuthController.php',
     "            'password' => $password,\n            'user_found' => $user ? true : false,",
     "            'user_found' => $user ? true : false,")

# 1.4 PublicWebsiteController: atomic views increment
edit('app/Http/Controllers/PublicWebsiteController.php',
     "        $post->increment('views_count');",
     "        \\Illuminate\\Support\\Facades\\DB::table('web_posts')->where('id', $post->id)->increment('views_count');")

# 1.5 OpsStudentAttendance: drop fields not in migration (idempotent)
edit('app/Models/OpsStudentAttendance.php',
     "        'notes',\n        'attachment_path',\n        'approval_status',\n        'approved_by',\n        'approved_at',",
     "        'notes',")

# ---------- 2. Access control: hasRole('admin') ----------
edit('app/Filament/Admin/Resources/StudentSavings/Tables/StudentSavingsTable.php',
     "if ($user->hasRole('admin')) {", "if ($user->role === 'admin') {}")
edit('app/Filament/Admin/Resources/Operasional/Tables/OpsStudentAttendancesTable.php',
     "if ($user->hasRole('admin')) {", "if ($user->role === 'admin') {", replace_all=True)
edit('app/Traits/BelongsToUser.php',
     "$user->hasRole('admin') || ($user->is_admin ?? false)",
     "$user->role === 'admin' || ($user->is_admin ?? false)")
edit('app/Filament/Admin/Resources/Teachers/TeacherResource.php',
     "auth()->user()->hasRole('admin')", "auth()->user()->role === 'admin'", replace_all=True)

# ---------- 3. Access control: hasRole(['admin','headmaster']) ----------
def edit_many(paths, old, new, replace_all=False):
    for fp in paths:
        edit(fp, old, new, replace_all=replace_all)

# non-negated guard
RES_NEG = [
    'app/Filament/Admin/Resources/Schedules/Schemas/ScheduleForm.php',
    'app/Filament/Admin/Resources/Schedules/Pages/ListSchedules.php',
    'app/Filament/Admin/Resources/Schedules/ScheduleResource.php',
    'app/Filament/Admin/Resources/Akademik/LearningObjectiveResource.php',
    'app/Filament/Admin/Resources/Akademik/Tables/LearningObjectiveTable.php',
    'app/Filament/Admin/Resources/Akademik/Tables/SumativeScopeTable.php',
    'app/Filament/Admin/Resources/Akademik/SumativeScopeResource.php',
    'app/Filament/Admin/Resources/Materials/MaterialResource.php',
    'app/Filament/Admin/Resources/AcademicCalendars/AcademicCalendarResource.php',
]
edit_many(RES_NEG,
          "if ($user->hasRole(['admin', 'headmaster'])) {",
          "if ($user->role === 'admin' || $user->role === 'headmaster') {}")

# negated guard
RES_NEGATED = [
    'app/Filament/Admin/Resources/Akademik/Pages/CreateLearningObjective.php',
    'app/Filament/Admin/Resources/Akademik/Pages/CreateSumativeScope.php',
    'app/Filament/Admin/Resources/Materials/Pages/CreateMaterial.php',
]
edit_many(RES_NEGATED,
          "if (! $user->hasRole(['admin', 'headmaster'])) {",
          "if (!($user->role === 'admin' || $user->role === 'headmaster')) {}")

# return statements in policies
POLICIES = [
    'app/Policies/SchedulePolicy.php',
    'app/Policies/SubjectPolicy.php',
    'app/Policies/AcademicYearPolicy.php',
    'app/Policies/SumativeScopePolicy.php',
    'app/Policies/AcademicCalendarPolicy.php',
    'app/Policies/MaterialPolicy.php',
    'app/Policies/LearningObjectivePolicy.php',
]
edit_many(POLICIES,
          "return $user->hasRole(['admin', 'headmaster']);",
          "return $user->role === 'admin' || $user->role === 'headmaster';")

# Pdf controllers: keep method_exists null-guard, swap inner hasRole
PDF_PATHS = [
    'app/Http/Controllers/Pdf/StudentAttendancePdfController.php',
    'app/Http/Controllers/Pdf/LearningObjectiveReportController.php',
    'app/Http/Controllers/Pdf/AcademicCalendarPdfController.php',
    'app/Http/Controllers/Pdf/StudentCompleteReportController.php',
    'app/Http/Controllers/Pdf/StudentSavingPdfController.php',
    'app/Http/Controllers/Pdf/StudentAgePdfController.php',
    'app/Http/Controllers/Pdf/AcademicCalendarWeeklyPdfController.php',
    'app/Http/Controllers/Pdf/SumativeScopeReportController.php',
    'app/Http/Controllers/Pdf/SchedulePdfController.php',
]
edit_many(PDF_PATHS,
          "method_exists($user, 'hasRole') && $user->hasRole(['admin', 'headmaster'])",
          "method_exists($user, 'hasRole') && ($user->role === 'admin' || $user->role === 'headmaster')")
