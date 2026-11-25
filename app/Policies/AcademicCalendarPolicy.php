<?php

namespace App\Policies;

use App\Models\AcademicCalendar;
use App\Models\User;

class AcademicCalendarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa melihat daftar (nanti difilter di Resource)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Admin & Headmaster bisa melihat semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        // Cek melalui relasi learning objective atau sumative scope terkait
        $lo = $academicCalendar->learningObjective;
        if (! $lo) {
            return false;
        }

        return $lo->teacher_id === $teacherId || optional($lo->sumativeScope)->teacher_id === $teacherId;
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
    public function update(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Admin & Headmaster bisa mengedit semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        $lo = $academicCalendar->learningObjective;
        if (! $lo) {
            return false;
        }

        return $lo->teacher_id === $teacherId || optional($lo->sumativeScope)->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcademicCalendar $academicCalendar): bool
    {
        // Admin & Headmaster bisa menghapus semua data
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        $lo = $academicCalendar->learningObjective;
        if (! $lo) {
            return false;
        }

        return $lo->teacher_id === $teacherId || optional($lo->sumativeScope)->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcademicCalendar $academicCalendar): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcademicCalendar $academicCalendar): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}