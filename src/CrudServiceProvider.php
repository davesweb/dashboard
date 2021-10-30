<?php

namespace Davesweb\Dashboard;

use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class CrudServiceProvider extends IlluminateServiceProvider
{
    public function boot(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerCrudRoutes();
    }

    private function registerCrudRoutes()
    {
        $locations = array_merge(config('dashboard.crud.locations', []), [__DIR__ . '/Crud' => 'Davesweb\\Dashboard\\Crud']);

        foreach ($locations as $location => $namespace) {
            $directory = new RecursiveDirectoryIterator($location);
            $iterator  = new RecursiveIteratorIterator($directory);
            $files     = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

            foreach ($files as $file) {
                $basename  = pathinfo($file[0], PATHINFO_FILENAME);
                $classname = $namespace . '\\' . $basename;

                if (class_exists($classname)) {
                    $object = resolve($classname);

                    if (!$object instanceof Crud) {
                        continue;
                    }

                    $object->registerRouters($this->app->make('router'));
                }
            }
        }
    }
}
