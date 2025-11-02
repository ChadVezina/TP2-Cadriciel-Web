<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function create()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return redirect()->route('etudiants.index');
        }
        return view('auth.create');
    }

    /**
     * Handle login attempt.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.max' => 'Le mot de passe ne peut pas dépasser 20 caractères.'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('etudiants.index'))
                ->with('success', 'Vous êtes connecté avec succès!');
        }

        return back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'])
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
