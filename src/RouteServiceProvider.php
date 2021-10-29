<?php

namespace Davesweb\Dashboard;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IlluminateRouteServiceProvider;

class RouteServiceProvider extends IlluminateRouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::prefix(config('dashboard.route'))
                ->middleware(config('dashboard.middleware'))
                ->name(config('dashboard.route-prefix'))
                ->group(__DIR__ . '/../routes/dashboard.php')
            ;
        });
    }
}
