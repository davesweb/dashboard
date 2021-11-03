<?php

declare(strict_types=1);

use Illuminate\Http\Request;

if (!function_exists('dashboard_route')) {
    function dashboard_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route(config('dashboard.route-prefix') . $name, $parameters, $absolute);
    }
}

if (!function_exists('full_url_with_query')) {
    function full_url_with_query(array $newQueryParams): string
    {
        /** @var Request $request */
        $request = resolve('request');

        return $request->fullUrlWithQuery($newQueryParams);
    }
}
