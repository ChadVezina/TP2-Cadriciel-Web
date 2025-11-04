<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * Attempt to log in a user.
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Log out the current user.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Regenerate session on successful login.
     */
    public function regenerateSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Get authenticated user.
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Check if user is authenticated.
     */
    public function check(): bool
    {
        return Auth::check();
    }
}
