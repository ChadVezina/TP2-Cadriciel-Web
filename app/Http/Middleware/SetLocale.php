<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, or use default
        $locale = Session::get('locale', config('app.locale', 'fr'));

        // Validate locale is supported
        $availableLocales = config('app.available_locales', ['fr' => 'Français', 'en' => 'English']);
        if (array_key_exists($locale, $availableLocales)) {
            App::setLocale($locale);
        } else {
            // Fallback to default locale if invalid locale in session
            App::setLocale(config('app.locale', 'fr'));
        }

        return $next($request);
    }
}
