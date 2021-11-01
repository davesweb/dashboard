<?php

namespace Davesweb\Dashboard\Http\Controllers;

use Davesweb\Dashboard\Crud\Users;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Http\RedirectResponse;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Requests\CrudIndexRequest;

class CrudController extends Controller
{
    public function index(CrudIndexRequest $request): Renderable
    {
        $crud = $this->crud();

        $table = resolve(Table::class, ['crud' => $crud]);

        $crud->index($table);

        // @todo add ordering and querying based on the request
        $items = $this->query()->paginate();

        return view('dashboard::crud.index', [
            'pageTitle' => $crud->plural(),
            'items'     => $items,
            'table'     => $table,
        ]);
    }

    public function trashed(CrudIndexRequest $request): Renderable
    {
        $crud = $this->crud();

        $table = resolve(Table::class, ['crud' => $crud]);

        $crud->index($table);

        // @todo add ordering and querying based on the request
        $items = $this->query()->onlyTrashed()->paginate();

        return view('dashboard::crud.index', [
            'pageTitle' => $crud->plural(),
            'items'     => $items,
            'table'     => $table,
        ]);
    }

    public function view(mixed $id): Renderable
    {
    }

    public function create(): Renderable
    {
    }

    public function store(): RedirectResponse
    {
    }

    public function edit(mixed $id): Renderable
    {
    }

    public function update(mixed $id): Renderable
    {
    }

    public function destroy(mixed $id): RedirectResponse
    {
    }

    public function destroyHard(mixed $id): RedirectResponse
    {
    }

    protected function crud(): Crud
    {
        // @todo Find the correct Crud class based on the route
        // For now, we only want to test

        // @todo Also keep a property to save the Crud class in so we don't new it ivery time
        return resolve(Users::class);
    }

    protected function query(): Builder
    {
        return $this->crud()->query();
    }
}
