<?php

namespace App\Traits;

use App\Models\User;

trait BelongsToUser
{
    /**
     * Cek apakah record ini milik user yang sedang login.
     * Sesuaikan kolom relasinya (misal: teacher_id, user_id, dll)
     */
    public static function canEditRecord(User $user, $record): bool
    {
        // Jika admin, bebaskan akses
        if ($user->role === 'admin' || ($user->is_admin ?? false)) {
            return true;
        }

        // Cek kepemilikan (sesuaikan kolom di tabel Anda)
        return $record->user_id === $user->id || $record->teacher_id === $user->id;
    }
}