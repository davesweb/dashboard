<?php

declare(strict_types=1);

namespace Davesweb\Dashboard;

use Illuminate\Support\Facades\Route;
use Davesweb\Dashboard\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as IlluminateRouteServiceProvider;

class RouteServiceProvider extends IlluminateRouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::prefix(config('dashboard.route'))
                ->middleware(config('dashboard.middleware', []))
                ->name(config('dashboard.route-prefix'))
                ->group(__DIR__ . '/../routes/dashboard.php')
            ;

            // We need some special routes for fortify because we can't change the route name that Fortify loads.
            // @todo Let fortify define the routes by setting the views option to true.
            Route::prefix(config('dashboard.route'))
                ->middleware(config('dashboard.middleware'))
                ->get('confirm-password', [LoginController::class, 'confirm'])
                ->name('password.confirm')
            ;

            Route::prefix(config('dashboard.route'))
                ->middleware(config('dashboard.middleware'))
                ->get('confirm-password', [LoginController::class, 'twoFactor'])
                ->name('two-factor.login')
            ;
        });
    }
}
