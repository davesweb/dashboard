<?php

namespace Davesweb\Dashboard\Services;

use Illuminate\Support\Str;
use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\Dashboard\Layout\Sidebar\Menu;
use Davesweb\Dashboard\Services\Table\Action;
use Illuminate\Database\Eloquent\SoftDeletes;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Davesweb\Dashboard\Http\Middleware\Authenticate;
use Davesweb\Dashboard\Http\Controllers\CrudController;

abstract class Crud
{
    public const ACTION_INDEX         = 'index';
    public const ACTION_INDEX_TRASHED = 'trashed';
    public const ACTION_SHOW          = 'show';
    public const ACTION_CREATE        = 'create';
    public const ACTION_UPDATE        = 'update';
    public const ACTION_DESTROY       = 'destroy';
    public const ACTION_DESTROY_HARD  = 'destroy-hard';

    protected string $model;

    protected string $controller = CrudController::class;

    protected ?Model $modelObject = null;

    public function singular(): string
    {
        return (string) Str::of($this->model)->classBasename()->singular();
    }

    public function plural(): string
    {
        return (string) Str::of($this->model)->classBasename()->plural();
    }

    public function pluralSlug(): string
    {
        return (string) Str::of($this->plural())->slug()->lower();
    }

    public function names(): array
    {
        return [$this->singular(), $this->plural()];
    }

    public function icon(): string
    {
        return 'fa fa-cogs';
    }

    public function routePrefix(): string
    {
        return Str::of($this->plural())->slug()->lower();
    }

    public function actions(): array
    {
        $actions = [self::ACTION_INDEX, self::ACTION_SHOW, self::ACTION_CREATE, self::ACTION_UPDATE, self::ACTION_DESTROY];

        if (in_array(SoftDeletes::class, class_uses_recursive($this->model), true)) {
            $actions[] = self::ACTION_INDEX_TRASHED;
            $actions[] = self::ACTION_DESTROY_HARD;
        }

        return $actions;
    }

    /**
     * @todo make icons configurable
     */
    public function getTableActions(): array
    {
        $tableActions = [];
        $actions      = $this->actions();

        if (in_array(self::ACTION_SHOW, $actions, true) && !request()->routeIs($this->getRouteNamePrefix() . 'show')) {
            $tableActions[] = new Action(
                title: __('View this :model', ['model' => $this->singular()]),
                route: $this->getRouteNamePrefix() . 'show',
                icon: 'fa fa-eye fa-fw',
            );
        }

        if (in_array(self::ACTION_UPDATE, $actions, true)) {
            $tableActions[] = new Action(
                title: __('Edit this :model', ['model' => $this->singular()]),
                route: $this->getRouteNamePrefix() . 'edit',
                icon: 'fa fa-pencil fa-fw',
            );
        }

        if (in_array(self::ACTION_DESTROY, $actions, true) && !request()->routeIs($this->getRouteNamePrefix() . 'trashed')) {
            $tableActions[] = new Action(
                title: __('Delete this :model', ['model' => $this->singular()]),
                route: $this->getRouteNamePrefix() . 'destroy',
                icon: 'fa fa-close fa-fw',
                formMethod: 'delete'
            );
        }

        if (in_array(self::ACTION_DESTROY, $actions, true) && request()->routeIs($this->getRouteNamePrefix() . 'trashed')) {
            $tableActions[] = new Action(
                title: __('Delete this :model permanently', ['model' => $this->singular()]),
                route: $this->getRouteNamePrefix() . 'destroy-hard',
                icon: 'fa fa-close fa-fw',
                formMethod: 'delete'
            );
        }

        return $tableActions;
    }

    public function registerRouters(Router $router): void
    {
        $router->group([
            'as'         => $this->getRouteNamePrefix(),
            'prefix'     => config('dashboard.route') . '/' . $this->routePrefix() . '/',
            'middleware' => array_merge(config('dashboard.middleware'), [Authenticate::class . ':dashboard']),
        ], function (Router $router) {
            $actions = $this->actions();

            if (in_array(self::ACTION_INDEX, $actions, true)) {
                $router->get('', [$this->controller, 'index'])->name('index');
            }

            if (in_array(self::ACTION_INDEX_TRASHED, $actions, true)) {
                $router->get('trashed', [$this->controller, 'trashed'])->name('trashed');
                $router->delete('destroy/{id}', [$this->controller, 'destroyHard'])->name('destroy-hard');
            }

            if (in_array(self::ACTION_SHOW, $actions, true)) {
                $router->get('{id}', [$this->controller, 'show'])->name('show');
            }

            if (in_array(self::ACTION_DESTROY, $actions, true)) {
                $router->delete('{id}', [$this->controller, 'destroy'])->name('destroy');
            }

            if (in_array(self::ACTION_CREATE, $actions, true)) {
                $router->get('create', [$this->controller, 'create'])->name('create');
                $router->post('', [$this->controller, 'store'])->name('store');
            }

            if (in_array(self::ACTION_UPDATE, $actions, true)) {
                $router->get('edit/{id}', [$this->controller, 'edit'])->name('edit');
                $router->put('edit/{id}', [$this->controller, 'update'])->name('update');
            }
        });
    }

    public function registerMenu(Sidebar $sidebar)
    {
        /** @var Menu $menu */
        $menu = $sidebar->getMenus()->first();

        if (in_array(self::ACTION_INDEX_TRASHED, $this->actions(), true)) {
            $submenu = Menu::make()
                ->link($this->plural(), route($this->getRouteNamePrefix() . 'index'))
                ->link(__('Trashed :models', ['models' => $this->plural()]), route($this->getRouteNamePrefix() . 'trashed'))
            ;

            $menu->link($this->plural(), null, $this->icon(), $submenu);

            return;
        }

        $menu->link($this->plural(), route($this->getRouteNamePrefix() . 'index'), $this->icon());
    }

    public function query(): Builder
    {
        return call_user_func($this->model . '::query');
    }

    public function model(mixed $id = null): Model
    {
        /** @var Model $model */
        $model = $this->modelObject ?? resolve($this->model);

        $this->modelObject = $model;

        if (null === $id) {
            return $model;
        }

        return $model->newQuery()->findOrFail($id);
    }

    abstract public function index(Table $table): void;

    public function trashed(Table $table): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function show(Table $table): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function store(Form $form): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function update(Form $form): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function submit(Form $form): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function delete(): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    protected function getRouteNamePrefix(): string
    {
        return config('dashboard.route-prefix') . $this->pluralSlug() . '.';
    }
}
