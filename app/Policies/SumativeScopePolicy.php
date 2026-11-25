<?php

namespace App\Policies;

use App\Models\SumativeScope;
use App\Models\User;

class SumativeScopePolicy
{
    public function viewAny(User $user): bool
    {
        // Semua user boleh masuk ke halaman daftar (nanti datanya difilter di controller)
        return true; 
    }

    public function view(User $user, SumativeScope $sumativeScope): bool
    {
        // Admin & Headmaster bisa melihat semua detail
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        // User biasa hanya bisa melihat datanya sendiri
        // $teacherId = optional($user->teacher()->first())->id;
        // return $sumativeScope->teacher_id === $teacherId;
        return $sumativeScope->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Semua user yang login boleh membuat data baru
        return true; 
    }

    public function update(User $user, SumativeScope $sumativeScope): bool
    {
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        return $sumativeScope->teacher_id === $teacherId;
    }

    public function delete(User $user, SumativeScope $sumativeScope): bool
    {
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        return $sumativeScope->teacher_id === $teacherId;
    }
}