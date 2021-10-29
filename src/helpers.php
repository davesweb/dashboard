<?php

if (!function_exists('dashboard_route')) {
    function dashboard_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route(config('dashboard.route-prefix') . $name, $parameters, $absolute);
    }
}
