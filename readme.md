# Davesweb Dashboard

An admin dashboard for Laravel applications. It comes with a default bootstrap 5 template, but it's 
100% customizable. You can use your own views and assets to either overwrite certain parts or 
use a completely different template.

Currently, this package is part of the davesweb repository, but it will be its own package later on.

### Custom views

### Custom assets

## Local Development

For local development, I recommend checking out the package with git and adding it as a path repository
to your composer file instead of constantly pushing updates and updating composer.

### Building assets

- Go to root folder of the package
- Run `npm install` and `npm run dev` (or `watch` or `prod`).
- Go to the root folder of the project your using this package in.
- Run `php artisan vendor:publish --provider=Davesweb\Dashboard\ServiceProvider --tag=public`
- The assets from the package are now available, use the asset helper for ease of use. 
