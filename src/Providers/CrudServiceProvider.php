<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Support\Facades\Route;
use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Services\CrudFinder;
use Illuminate\Routing\Events\RouteMatched;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;
use Davesweb\Dashboard\ModelTranslators\DaveswebTranslator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class CrudServiceProvider extends IlluminateServiceProvider
{
    protected ?Collection $cruds = null;

    public function boot(): void
    {
        Route::matched(function (RouteMatched $route) {
            $mainMenu = Menu::make(__('Menu'));
            $mainMenu->link(__('Dashboard'), dashboard_route('index'), new HtmlString('<i class="fa fa-dashboard"></i>'), null, -10);

            $helpMenu = Menu::make(__('Help'));
            $helpMenu->link(__('Documentation'), 'https://davesweb.github.io/dashboard/', new HtmlString('<i class="fa fa-info-circle"></i>'))->targetBlank();
            $helpMenu->link(__('Updates'), dashboard_route('updates'), new HtmlString('<i class="fa fa-wrench"></i>')); // @todo Add check for latest version + controller
            $helpMenu->link(__('Credits'), dashboard_route('credits'), new HtmlString('<i class="fab fa-creative-commons-by"></i>'))->targetBlank();

            $sidebar = Sidebar::factory();
            $sidebar->menu($mainMenu, -10);
            $sidebar->divider(99);
            $sidebar->menu($helpMenu, 100);

            $this->registerCrudMenus();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind(TranslatesModelAttributes::class, config('dashboard.translator', DaveswebTranslator::class));

        $this->registerCrud();
    }

    private function registerCrud()
    {
        foreach ($this->findCruds() as $crud) {
            $crud->registerRoutes($this->app->make('router'));
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

        $locations = array_merge(config('dashboard.crud.locations', []), [__DIR__ . '/../Crud' => 'Davesweb\\Dashboard\\Crud']);

        //if ($this->app->runningUnitTests()) {
        //    $locations[__DIR__ . '/../../tests/Crud'] = '\\Davesweb\\Dashboard\\Tests\\Crud';
        //}

        $this->cruds = $finder->findAllByLocations($locations);

        return $this->cruds;
    }
}
