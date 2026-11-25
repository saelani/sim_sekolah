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

# Policy `if` guards (same pattern as resources, but in app/Policies)
POLICY_IF = [
    'app/Policies/SchedulePolicy.php',
    'app/Policies/SubjectPolicy.php',
    'app/Policies/AcademicYearPolicy.php',
    'app/Policies/SumativeScopePolicy.php',
    'app/Policies/AcademicCalendarPolicy.php',
    'app/Policies/MaterialPolicy.php',
    'app/Policies/LearningObjectivePolicy.php',
]
edit_many = None
# handle each file individually to apply replace_all where needed
for fp in POLICY_IF:
    with open(os.path.join(base, fp), encoding='utf-8') as f:
        c = f.read()
    n_if = c.count("if ($user->hasRole(['admin', 'headmaster'])) {")
    if n_if:
        c = c.replace("if ($user->hasRole(['admin', 'headmaster'])) {",
                      "if ($user->role === 'admin' || $user->role === 'headmaster') {}")
        with open(os.path.join(base, fp), 'w', encoding='utf-8') as f:
            f.write(c)
        print(f"  [OK] {n_if}x: {fp} :: policy if-guard")

# Verify OpsStudentAttendance no longer has the extra (drift) fields
p = os.path.join(base, 'app/Models/OpsStudentAttendance.php')
c = open(p, encoding='utf-8').read()
for token in ['attachment_path', 'approval_status', 'approved_by', 'approved_at']:
    if token in c:
        print(f"  [WARN] still present: {p} :: {token}")
    else:
        print(f"  [OK] removed: {p} :: {token}")
