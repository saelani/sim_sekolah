<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user boleh melihat daftar
    }

    public function view(User $user, AcademicYear $academicYear): bool
    {
        return true; // Semua user boleh melihat detail
    }

    public function create(User $user): bool
    {
        // Hanya admin atau headmaster yang boleh membuat data baru
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    public function update(User $user, AcademicYear $academicYear): bool
    {
        // Hanya admin atau headmaster yang boleh mengedit
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    public function delete(User $user, AcademicYear $academicYear): bool
    {
        // Hanya admin atau headmaster yang boleh menghapus
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}