<?php

declare(strict_types=1);

namespace Davesweb\Dashboard;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Support\Facades\Route;
use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Services\CrudFinder;
use Illuminate\Routing\Events\RouteMatched;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class CrudServiceProvider extends IlluminateServiceProvider
{
    protected ?Collection $cruds = null;

    public function boot(): void
    {
        Route::matched(function (RouteMatched $route) {
            $mainMenu = Menu::make();
            $mainMenu->link(__('Dashboard'), dashboard_route('index'), new HtmlString('<i class="fa fa-dashboard"></i>'), null, -10);

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
        $this->app->bind(TranslatesModelAttributes::class, config('dashboard.translator'));

        $this->registerCrud();
    }

    private function registerCrud()
    {
        foreach ($this->findCruds() as $crud) {
            $crud->registerRouters($this->app->make('router'));
        }
    }

    private function registerCrudMenus()
    {
        foreach ($this->findCruds() as $crud) {
            $crud->registerMenu(Sidebar::factory());
        }
    }

    /**
     * @return Collection|Crud[]
     */
    private function findCruds(): Collection
    {
        if ($this->cruds instanceof Collection && $this->cruds->count() > 0) {
            return $this->cruds;
        }

        /** @var CrudFinder $finder */
        $finder = resolve(CrudFinder::class);

        $this->cruds = $finder->findAllByLocations(array_merge(config('dashboard.crud.locations', []), [__DIR__ . '/Crud' => 'Davesweb\\Dashboard\\Crud']));

        return $this->cruds;
    }
}
