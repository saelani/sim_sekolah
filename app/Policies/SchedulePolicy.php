<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa melihat halaman index (nanti difilter di Resource)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        // Admin & Headmaster memiliki akses penuh
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        // Ambil ID guru dari user yang sedang login
        $teacherId = optional($user->teacher()->first())->id;

        // Cek kepemilikan melalui relasi bertingkat: Schedule -> schoolClass -> teacher_id
        return optional($schedule->schoolClass)->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Diizinkan agar tombol Create/Tambah muncul
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        // Admin & Headmaster memiliki akses penuh
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        // Cek kepemilikan melalui relasi bertingkat
        return optional($schedule->schoolClass)->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        // Admin & Headmaster memiliki akses penuh
        if ($user->role === 'admin' || $user->role === 'headmaster') {
            return true;
        }

        $teacherId = optional($user->teacher()->first())->id;

        // Cek kepemilikan melalui relasi bertingkat
        return optional($schedule->schoolClass)->teacher_id === $teacherId;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return $user->role === 'admin' || $user->role === 'headmaster';
    }
}