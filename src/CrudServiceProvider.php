<?php

namespace Davesweb\Dashboard;

use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Support\Facades\Route;
use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class CrudServiceProvider extends IlluminateServiceProvider
{
    public function boot(): void
    {
        Route::matched(function () {
            $mainMenu = Menu::make();
            $mainMenu->link(__('Dashboard'), dashboard_route('index'), 'fa fa-dashboard', null, -10);

            $sidebar = Sidebar::factory();
            $sidebar->menu($mainMenu, 0);

            $this->registerCrudMenus();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerCrud();
    }

    private function registerCrud()
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

    /**
     * @todo Cache Crud objects
     */
    private function registerCrudMenus()
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

                    $object->registerMenu(Sidebar::factory());
                }
            }
        }
    }
}
