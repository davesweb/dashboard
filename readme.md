# Davesweb Dashboard

An admin dashboard for Laravel applications. It comes with a default bootstrap 5 template, but it's 
100% customizable. You can use your own views and assets to either overwrite certain parts or 
use a completely different template.

## Installation

Via composer
```shell
composer require davesweb/dashboard
```

After that, publish the assets by running 

```shell
php artisan vendor:publish --provider=Davesweb\Dashboard\ServiceProvider
```

Run the migrations

```shell
php artisan migrate
```

The dashboard is now available, by default at `yoursite.ext/dashboard`. You can configure the 
route in de `dashboard.php` config file.

Create the first user for your dashboard by running the artisan command.

```shell
php artisan dashboard:user
```

## Local Development

For local development, I recommend checking out the package with git and adding it as a path repository
to your composer file instead of constantly pushing updates and updating composer.

### Building assets

- Go to root folder of the package
- Run `npm install` and `npm run dev` (or `watch` or `prod`).
- Go to the root folder of the project you're using this package in.
- Run `php artisan vendor:publish --provider=Davesweb\Dashboard\ServiceProvider --tag=public --force`
- The assets from the package are now available, use the asset helper for ease of use. 

## Todos

- ~~Instead of using CSS class names for the icons, use HtmlString objects so we aren't required to use 
  an icon library that is used by specifying class names.~~
- Add a check for cached routes to the crud route registration and add a command to cache the menu 
  items of crud classes, so we don't need to instantiate every single crud class on every request.