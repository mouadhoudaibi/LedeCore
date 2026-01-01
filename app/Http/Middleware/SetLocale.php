<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Get locale from session, default to French
        $locale = session('locale', 'fr');

        // Validate locale is supported
        if (! in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }

        // Set the application locale
        app()->setLocale($locale);

        return $next($request);
    }
}
