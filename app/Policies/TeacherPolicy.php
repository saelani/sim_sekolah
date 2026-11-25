<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use App\Traits\BelongsToUser;

class TeacherPolicy
{
    use BelongsToUser;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Teacher $teacher): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Teacher $teacher): bool
    {
        // Menggunakan Trait atau fungsi pengecekan kepemilikan
        return self::canEditRecord($user, $teacher);
    }

    public function delete(User $user, Teacher $teacher): bool
    {
        return self::canEditRecord($user, $teacher);
    }
}