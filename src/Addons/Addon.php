<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Addons;

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;

abstract class Addon
{
    public function register(): void
    {
        // Can be implemented in Addons, but is not required
    }

    public function registerMenu(Sidebar $sidebar): void
    {
        // Can be implemented in Addons, but is not required
    }

    public function registerRoutes(Router $router): void
    {
        // Can be implemented in Addons, but is not required
    }
}
