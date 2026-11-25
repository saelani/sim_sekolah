#!/usr/bin/env python3
import os

base = '/home/saelani/lara/sim_sekolah'

def fix(path, old, new, count):
    full = os.path.join(base, path)
    with open(full, encoding='utf-8') as f:
        c = f.read()
    n = c.count(old)
    if n == 0:
        print(f"  [WARN] 0-hits: {path} :: {old[:60]!r}")
        return
    c = c.replace(old, new)
    print(f"  [OK] {n}x: {path} :: {old[:60]!r}")
    with open(full, 'w', encoding='utf-8') as f:
        f.write(c)

# All files that have an orphaned 'if (...)' where body was removed by earlier edit.
FILES_PAT1 = [  # single role
    'app/Filament/Admin/Resources/StudentSavings/Tables/StudentSavingsTable.php',
    'app/Filament/Admin/Resources/Operasional/Tables/OpsStudentAttendancesTable.php',
]
FILES_PAT2 = [  # multi role
    'app/Filament/Admin/Resources/Schedules/Schemas/ScheduleForm.php',
    'app/Filament/Admin/Resources/Schedules/Pages/ListSchedules.php',
    'app/Filament/Admin/Resources/Schedules/ScheduleResource.php',
    'app/Filament/Admin/Resources/Akademik/LearningObjectiveResource.php',
    'app/Filament/Admin/Resources/Akademik/Tables/LearningObjectiveTable.php',
    'app/Filament/Admin/Resources/Akademik/Tables/SumativeScopeTable.php',
    'app/Filament/Admin/Resources/Akademik/SumativeScopeResource.php',
    'app/Filament/Admin/Resources/Materials/MaterialResource.php',
    'app/Filament/Admin/Resources/AcademicCalendars/AcademicCalendarResource.php',
    'app/Policies/SchedulePolicy.php',
    'app/Policies/SubjectPolicy.php',
    'app/Policies/AcademicYearPolicy.php',
    'app/Policies/SumativeScopePolicy.php',
    'app/Policies/AcademicCalendarPolicy.php',
    'app/Policies/MaterialPolicy.php',
    'app/Policies/LearningObjectivePolicy.php',
]
FILES_PAT3 = [  # negated multi role
    'app/Filament/Admin/Resources/Akademik/Pages/CreateLearningObjective.php',
    'app/Filament/Admin/Resources/Akademik/Pages/CreateSumativeScope.php',
    'app/Filament/Admin/Resources/Materials/Pages/CreateMaterial.php',
]

for fp in FILES_PAT1:
    fix(fp, "if ($user->role === 'admin') {}", "if ($user->role === 'admin') {", count=True)
for fp in FILES_PAT2:
    fix(fp, "if ($user->role === 'admin' || $user->role === 'headmaster') {}", "if ($user->role === 'admin' || $user->role === 'headmaster') {", count=True)
for fp in FILES_PAT3:
    fix(fp, "if (!($user->role === 'admin' || $user->role === 'headmaster')) {}", "if (!($user->role === 'admin' || $user->role === 'headmaster')) {", count=True)
