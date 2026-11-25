<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Material $material): bool
    {
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        return $material->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Material $material): bool
    {
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        return $material->user_id === $user->id;
    }

    public function delete(User $user, Material $material): bool
    {
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        return $material->user_id === $user->id;
    }

    public function restore(User $user, Material $material): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    public function forceDelete(User $user, Material $material): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}