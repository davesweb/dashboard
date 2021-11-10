<?php

declare(strict_types=1);

namespace Davesweb\Dashboard;

use Davesweb\Dashboard\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\EloquentUserProvider;
use Davesweb\Dashboard\Console\Commands\CreateCrudCommand;
use Davesweb\Dashboard\Console\Commands\CreateDashboardUserCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/dashboard.php' => config_path('dashboard.php'),
            __DIR__ . '/../config/fortify.php'   => config_path('fortify.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/dashboard'),
            __DIR__ . '/../public/images' => public_path('images'),
            __DIR__ . '/../public/fonts' => public_path('fonts'),
        ], 'public');
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        //$this->mergeConfigFrom(__DIR__ . '/../config/dashboard.php', 'dashboard');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard');

        // We'll use the SessionGuard driver with the dashboard provider
        Config::set('auth.guards.dashboard', [
            'driver'   => 'session',
            'provider' => 'dashboard',
        ]);

        // We'll use the EloquentUserProvider driver with our custom model
        Config::set('auth.providers.dashboard', [
            'driver' => 'eloquent',
            'model'  => User::class,
        ]);
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateDashboardUserCommand::class,
                CreateCrudCommand::class,
            ]);
        }
    }
}
