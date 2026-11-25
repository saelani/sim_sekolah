<?php

namespace App\Policies;

use App\Models\LearningObjective;
use App\Models\User;

class LearningObjectivePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user boleh melihat daftar (nanti difilter di Resource)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LearningObjective $learningObjective): bool
    {
        // Admin & Headmaster bisa melihat semua detail
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        // Guru biasa hanya bisa melihat datanya sendiri
        $teacherId = optional($user->teacher()->first())->id;
        return $learningObjective->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Semua user yang login boleh membuat data baru
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LearningObjective $learningObjective): bool
    {
        // Admin & Headmaster bisa mengedit semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        // Guru biasa hanya bisa mengedit datanya sendiri
        $teacherId = optional($user->teacher()->first())->id;
        return $learningObjective->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LearningObjective $learningObjective): bool
    {
        // Admin & Headmaster bisa menghapus semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        // Guru biasa hanya bisa menghapus datanya sendiri
        $teacherId = optional($user->teacher()->first())->id;
        return $learningObjective->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LearningObjective $learningObjective): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LearningObjective $learningObjective): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}