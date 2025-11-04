<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur AuthController
 * 
 * Gère l'authentification des utilisateurs (connexion et déconnexion).
 */
class AuthController extends Controller
{
    /**
     * Crée une nouvelle instance du contrôleur.
     * 
     * @param AuthService $authService Service gérant la logique d'authentification
     */
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Affiche le formulaire de connexion.
     * 
     * Si l'utilisateur est déjà connecté, le redirige vers la liste des étudiants.
     * 
     * @return View|RedirectResponse Vue du formulaire ou redirection
     */
    public function create(): View|RedirectResponse
    {
        if ($this->authService->check()) {
            return redirect()->route('etudiants.index');
        }
        return view('auth.create');
    }

    /**
     * Traite une tentative de connexion.
     * 
     * En cas de succès, régénère la session et redirige vers la page initialement demandée.
     * En cas d'échec, retourne au formulaire avec un message d'erreur.
     * 
     * @param LoginRequest $request Requête validée contenant les identifiants
     * @return RedirectResponse Redirection appropriée selon le résultat
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
     * Déconnecte l'utilisateur actuellement connecté.
     * 
     * Invalide la session et redirige vers le formulaire de connexion.
     * 
     * @param Request $request Requête HTTP
     * @return RedirectResponse Redirection vers la page de connexion
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authService->logout($request);
        return redirect()
            ->route('login')
            ->with('success', __('auth.logout_success'));
    }
}
