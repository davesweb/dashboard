<?php

namespace Davesweb\Dashboard\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * {@inheritdoc}
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route(config('dashboard.route-prefix') . 'login');
        }
    }
}
