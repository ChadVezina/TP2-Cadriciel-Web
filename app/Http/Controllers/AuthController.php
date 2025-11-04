<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Display the login form.
     */
    public function create(): View|RedirectResponse
    {
        if ($this->authService->check()) {
            return redirect()->route('etudiants.index');
        }
        return view('auth.create');
    }

    /**
     * Handle login attempt.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if ($this->authService->login($credentials, $remember)) {
            $this->authService->regenerateSession($request);
            return redirect()
                ->intended(route('etudiants.index'))
            ->with('success', __('auth.login_success'));
        }

        return back()
            ->withErrors(['email' => __('auth.invalid_credentials')])
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout($request);
        return redirect()
            ->route('login')
            ->with('success', __('auth.logout_success'));
    }
}
