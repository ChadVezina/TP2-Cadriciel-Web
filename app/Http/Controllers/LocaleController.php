<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Contrôleur LocaleController
 * 
 * Gère le changement de langue de l'application.
 */
class LocaleController extends Controller
{
    /**
     * Change la langue de l'application.
     * 
     * Stocke la locale choisie en session et l'applique immédiatement.
     * Redirige ensuite vers la page précédente.
     * 
     * @param Request $request Requête HTTP
     * @param string $locale Nouvelle langue (fr ou en)
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page précédente
     */
    public function change(Request $request, string $locale)
    {
        // Valide que la locale est supportée
        if (!array_key_exists($locale, config('app.available_locales'))) {
            abort(400, 'Locale not supported');
        }

        // Stocke la locale en session
        Session::put('locale', $locale);
        // L'applique également immédiatement pour la requête actuelle
        App::setLocale($locale);

        // Redirige vers la page précédente
        return redirect()->back();
    }
}
