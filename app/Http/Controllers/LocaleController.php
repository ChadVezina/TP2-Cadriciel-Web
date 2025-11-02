<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Change the application locale
     */
    public function change(Request $request, string $locale)
    {
        // Validate that the locale is supported
        if (!array_key_exists($locale, config('app.available_locales'))) {
            abort(400, 'Locale not supported');
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Redirect back to previous page
        return redirect()->back();
    }
}
