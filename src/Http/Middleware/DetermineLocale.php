<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetermineLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $availableLocales = config('dashboard.available-locales', []);

        $locale = $request->get('locale', $request->cookie('dashboard-locale', config('dashboard.default-locale')));

        if (!array_key_exists($locale, $availableLocales)) {
            $locale = config('dashboard.default-locale', 'en');
        }

        app()->setLocale($locale);

        $response = $next($request);

        $response->withCookie(cookie('dashboard-locale', $locale, 365 * 24 * 60, '/'));

        return $response;
    }
}
