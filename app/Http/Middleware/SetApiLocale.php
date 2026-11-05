<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['id', 'en'];

        $locale = $request->query('locale')
            ?? $request->header('X-Locale')
            ?? substr((string) $request->header('Accept-Language', ''), 0, 2);

        if (in_array($locale, $supportedLocales, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
