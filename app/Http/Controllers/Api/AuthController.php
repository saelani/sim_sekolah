<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    /**
     * Login the user and return a Sanctum token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // If the user is part of a "web" guard (session login) we keep the session
        // and also issue a personal access token so the Flutter API client can use it.
        if (Auth::guard('web')->once($credentials, $credentials['remember'] ?? false)) {
            $token = $user->createToken('sim_sekolah_api')->plainTextToken;

            return $this->success([
                'user' => $this->userResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration', 120) ?? 120,
            ]);
        }

        return $this->error('Unable to authenticate.', 401);
    }

    /**
     * Log the user out (invalidate the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return $this->success(['message' => 'Logged out.']);
    }

    /**
     * Return a minimal user resource array.
     */
    protected function userResource(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
