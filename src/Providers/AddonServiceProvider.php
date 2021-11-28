<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Providers;

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Addons\Addon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Events\RouteMatched;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Http\Middleware\Authenticate;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class AddonServiceProvider extends IlluminateServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerAddons($this->getRegisteredAddons());
    }

    private function getRegisteredAddons(): array
    {
        /** @var Filesystem $files */
        $files = $this->app->make('files');

        return $files->getRequire($this->app->basePath('bootstrap/cache/dashboard.php'));
    }

    private function registerAddons(array $manifest): void
    {
        foreach ($manifest as $name => $data) {
            foreach ($data['addons'] ?? [] as $class) {
                $addon = $this->resolveAddon($class);

                $this->registerAddonRoutes($addon, $this->getRouter());

                Route::matched(function (RouteMatched $route) use ($addon) {
                    $addon->registerMenu(Sidebar::factory());

                    $addon->register();
                });
            }
        }
    }

    private function resolveAddon(string $addon): Addon
    {
        /** @var Addon $object */
        $object = resolve($addon);

        return $object;
    }

    private function getRouter(): Router
    {
        /** @var Router $router */
        $router = $this->app->make('router');

        return $router;
    }

    private function registerAddonRoutes(Addon $addon, Router $router): void
    {
        $router->group([
            'prefix'     => config('dashboard.route'),
            'as'         => config('dashboard.route-prefix'),
            'middleware' => array_merge(config('dashboard.middleware', []), [Authenticate::class . ':dashboard']),
        ], function (Router $router) use ($addon) {
            $addon->registerRoutes($router);
        });
    }
}
