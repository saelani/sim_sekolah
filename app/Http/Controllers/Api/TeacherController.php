<?php

namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TeacherController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin', 'headmaster', 'teacher'], true)) {
            return $this->error('Akses ditolak.', 403);
        }

        $teachers = Teacher::with('User')->orderBy('name', 'asc')->get();

        return $this->success($teachers->map(function (Teacher $t) {
            return [
                'id' => $t->id,
                'user_id' => $t->user_id,
                'nip' => $t->nip,
                'nuptk' => $t->nuptk,
                'name' => $t->name,
                'front_title' => $t->front_title,
                'back_title' => $t->back_title,
                'gender' => $t->gender,
                'role_type' => $t->role_type,
                'is_active' => (bool) $t->is_active,
                'user' => $t->User ? [
                    'id' => $t->User->id,
                    'name' => $t->User->name,
                    'email' => $t->User->email,
                    'role' => $t->User->role,
                ] : null,
            ];
        })->toArray());
    }
}
