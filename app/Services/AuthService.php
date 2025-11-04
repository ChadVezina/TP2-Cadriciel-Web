<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Service AuthService
 * 
 * Gère la logique métier liée à l'authentification des utilisateurs.
 * Fournit des méthodes pour la connexion, la déconnexion et la gestion des sessions.
 */
class AuthService
{
    /**
     * Tente de connecter un utilisateur avec les identifiants fournis.
     * 
     * @param array $credentials Identifiants de connexion (email et password)
     * @param bool $remember Si true, garde l'utilisateur connecté (défaut: false)
     * @return bool True si la connexion a réussi
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Déconnecte l'utilisateur actuellement connecté.
     * 
     * Invalide la session et régénère le token CSRF pour des raisons de sécurité.
     * 
     * @param Request $request Requête HTTP contenant la session
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Régénère l'identifiant de session après une connexion réussie.
     * 
     * Cette méthode prévient les attaques de fixation de session.
     * 
     * @param Request $request Requête HTTP contenant la session
     * @return void
     */
    public function regenerateSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Récupère l'utilisateur actuellement authentifié.
     * 
     * @return User|null Utilisateur connecté ou null si non authentifié
     */
    public function getAuthenticatedUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Vérifie si un utilisateur est actuellement authentifié.
     * 
     * @return bool True si un utilisateur est connecté
     */
    public function check(): bool
    {
        return Auth::check();
    }
}
