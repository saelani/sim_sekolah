<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Semua user boleh melihat daftar
    }

    public function view(User $user, Subject $subject): bool
    {
        return true; // Semua user boleh melihat detail
    }

    public function create(User $user): bool
    {
        // Hanya admin atau headmaster yang boleh membuat data baru
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    public function update(User $user, Subject $subject): bool
    {
        // Hanya admin atau headmaster yang boleh mengedit
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    public function delete(User $user, Subject $subject): bool
    {
        // Hanya admin atau headmaster yang boleh menghapus
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}