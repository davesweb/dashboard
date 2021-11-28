# Addons

Addons are another way to add custom functionality to your dashboard. You can add anything you want by creating 
your own crud controllers and crud classes, but sometimes that isn't the correct place for your functionality. Or 
maybe you want to re-use the same functionality across multiple projects, or even make it available as an open 
source package so everyone can use it.

An addon is nothing more than a composer package. Registering the package into your dashboard works in the same 
way as Laravel's package discovery. You add your addon registration class to your `composer.json` file and run 
the addon discover command.

## Installing an addon

An addon is a composer package, so to install it simply run the composer require command with the package's name. 
After that, run the `php artisan dasboard:addons` artisan command to make it available in your dashboard.

> The addon discover command must be run after every composer install, so you might want to add it to your 
> composer `post-autoload-dump` scripts after the laravel discover command:
>
> **composer.json**
> ```json
> {
>   "scripts": {
>     "post-autoload-dump": [
>       "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
>       "@php artisan package:discover --ansi",
>       "@php artisan dashboard:addons --ansi"
>     ]
>   }
> }
> ```

## Creating an addon

An addon for the dashboard is nothing more than a Laravel composer package with just a few extra things. So to 
start you might want to read the [Laravel document about creating packages](https://laravel.com/docs/packages). You 
can add everything you can add in a Laravel package, including views, configuration files, database migrations and 
routes (though the addon structure has a separate routing option you're advised to use).

### The `Addon` class

Just like you need a `ServiceProvider` class for your Laravel package, you need an `Addon` class for your addon. 
In this class you may register everything you need for your addon, including menu items and routes. Your addon 
class should extend the `Davesweb\Dashboard\Addons\Addon` base class. There are three optional methods you can 
implement to register components: `register()`, `registerMenu(Sidebar $sidebar)` and 
`registerRoutes(Router $router)`.

```php
<?php

namespace My\Addon;

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Addons\Addon as BaseAddon;

class Addon extends BaseAddon
{
    public function register(): void
    {
    }
    
    public function registerMenu(Sidebar $sidebar): void
    {
    }

    public function registerRoutes(Router $router): void
    {
    }
}
```

### Routing

In your addon class you can implement the `registerRoutes(Router $router)` method. This method reveives an 
`Illuminate\Routing\Router` object with the path prefix, name prefix and configured middlewares for the 
dashboard already set. It is advised you always use this method for registering your routes instead of a route
file because of this. Of course, if you don't need or want this things, you are off course free to use the 
traditional Laravel way instead.

```php
<?php

namespace My\Addon;

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Addons\Addon as BaseAddon;

class Addon extends BaseAddon
{
    public function registerRoutes(Router $router): void
    {
        $router->get('my-addon', function() {
            return 'Hello world, this is my first addon!';
        })->name('my-addon');
    }
}
```

Once you've registered your routes like this, you may use the `dashboard_route()` helper function to get the route 
path. This helper works exactly the same as the Laravel `route()` helper, except that it already sets the name 
prefix that is configured. In the above example, you can access the route path by calling 
`dashboard_route('my-addon')`.

### Menu items

To register menu items for your addon you may implement the `registerMenu(Sidebar $sidebar)` method in your addon 
class. This method receives a `Davesweb\Dashboard\Layout\Sidebar\Sidebar` object.

```php
<?php

namespace My\Addon;

use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Addons\Addon as BaseAddon;

class Addon extends BaseAddon
{   
    public function registerMenu(Sidebar $sidebar): void
    {
        $menu = Menu::make(__('My addon'));
        $menu->link(__('My addon link'), dashboard_route('my-addon'));

        $sidebar->menu($menu, 10);
    }
}
```

This method works in the same way as registering Sidebar items for normal crud classes, so please refer to the 
[Sidebar documentation](sidebar.html) for more details and examples.

### Register your addon

In order for your addon to be used by the dashboard you need to register it. You do this by adding it to the extra
section in your `composer.json` in the same way you register Laravel service providers.

**composer.json**
```json
{
  "extra": {
    "laravel": {
      "providers": []
    },
    "dashboard": {
      "addons": [
        "Your\\Addon\\Registration\\Class"
      ]
    }
  }
}
```

After installing the addon via composer, run the discover command:

`php artisan dashboard:addons`

Now your addon is registered into the dashboard and is available to use.

## Useful addons

Below is a list of open source addons created by the community. If you've created an addon you think is useful, 
please open a PR with your addon added to the list:

- [Dashboard](https://githib.com/davesweb/dashboard)