<?php

namespace Davesweb\Dashboard;

use Davesweb\Dashboard\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\EloquentUserProvider;
use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/dashboard.php' => config_path('dashboard.php')], 'config');
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/dashboard'),
        ], 'public');

        $this->mergeConfigFrom(__DIR__ . '/../config/dashboard.php', 'dashboard');
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

        Route::matched(function () {
            Sidebar::factory()
                ->menu(
                    Menu::make()
                        ->link(__('Dashboard'), dashboard_route('index'), 'fa fa-dashboard'),
                    //->link(__('Users'), '#', 'fa fa-users', Menu::make('Users menu')->link(__('Index'), '#')),
                    0
                )
            ;
        });
    }

    public function register()
    {
    }
}
