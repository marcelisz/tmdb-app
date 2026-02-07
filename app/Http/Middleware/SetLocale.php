<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Parse Accept-Language header (e.g. "pl,en;q=0.9")
        $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE', 'en'), 0, 2);

        $supported = ['en', 'pl', 'de'];

        if (in_array($locale, $supported)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en'); // Default language
        }

        return $next($request);
    }
}
