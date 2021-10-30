<?php

namespace Davesweb\Dashboard\Services;

use Illuminate\Support\Str;
use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\Dashboard\Http\Middleware\Authenticate;
use Davesweb\Dashboard\Http\Controllers\CrudController;

abstract class Crud
{
    protected string $model;

    protected string $controller = CrudController::class;

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

    public function routePrefix(): string
    {
        return Str::of($this->plural())->slug()->lower();
    }

    public function actions(): array
    {
        return ['index', 'view', 'create', 'update', 'delete'];
    }

    public function registerRouters(Router $router): void
    {
        $router->group([
            'as'         => config('dashboard.route-prefix') . $this->pluralSlug() . '.',
            'prefix'     => config('dashboard.route') . '/' . $this->routePrefix() . '/',
            'middleware' => array_merge(config('dashboard.middleware'), [Authenticate::class . ':dashboard']),
        ], function (Router $router) {
            $actions = $this->actions();

            if (in_array('index', $actions, true)) {
                $router->get('', [$this->controller, 'index'])->name('index');
            }

            if (in_array('view', $actions, true)) {
                $router->get('/{id}', [$this->controller, 'view'])->name('view');
            }

            if (in_array('create', $actions, true)) {
                $router->get('create', [$this->controller, 'create'])->name('create');
                $router->post('', [$this->controller, 'store'])->name('store');
            }

            if (in_array('update', $actions, true)) {
                $router->get('edit/{id}', [$this->controller, 'edit'])->name('edit');
                $router->put('edit/{id}', [$this->controller, 'update'])->name('update');
            }

            if (in_array('delete', $actions, true)) {
                $router->delete('{id}', [$this->controller, 'destroy'])->name('destroy');
            }
        });
    }

    public function query(): Builder
    {
        return call_user_func($this->model . '::query');
    }

    public function index(Table $table): void
    {
        // This method can be implemented in child classes, but it isn't required so we keep the body empty.
    }

    public function view(): void
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
}
