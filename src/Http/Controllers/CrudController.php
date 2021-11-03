<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Davesweb\Dashboard\Crud\Users;
use Davesweb\Dashboard\Services\Crud;
use Illuminate\Http\RedirectResponse;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Requests\CrudShowRequest;
use Davesweb\Dashboard\Http\Requests\CrudIndexRequest;

class CrudController extends Controller
{
    public function index(CrudIndexRequest $request): Renderable
    {
        $locale = $request->getCrudLocale();

        $crud = $this->crud();

        /** @var Table $table */
        $table = resolve(Table::class, ['crud' => $crud]);

        $crud->index($table);

        // @todo add ordering based on the request
        $query = $this->query();

        if ($request->hasSearch()) {
            $crud->search($query, $table->getSearchableColumns(), $request->getCrudLocale(), $request->getSearchQuery());
        }

        $items = $query->paginate();

        return view('dashboard::crud.index', [
            'pageTitle'   => $crud->plural(),
            'items'       => $items,
            'table'       => $table,
            'crudLocale'  => $locale,
            'searchQuery' => $request->getSearchQuery(),
        ]);
    }

    public function trashed(CrudIndexRequest $request): Renderable
    {
        $locale = $request->getCrudLocale();

        $crud = $this->crud();

        /** @var Table $table */
        $table = resolve(Table::class, ['crud' => $crud]);

        $crud->trashed($table);

        if (0 === count($table->getColumns())) {
            $crud->index($table);
        }

        // @todo add ordering based on the request
        $query = $this->query()->onlyTrashed();

        if ($request->hasSearch()) {
            $crud->search($query, $table->getSearchableColumns(), $request->getCrudLocale(), $request->getSearchQuery());
        }

        $items = $query->paginate();

        return view('dashboard::crud.index', [
            'pageTitle'   => __('Trashed :model', ['model' => $crud->plural()]),
            'items'       => $items,
            'table'       => $table,
            'crudLocale'  => $locale,
            'searchQuery' => $request->getSearchQuery(),
        ]);
    }

    public function show(CrudShowRequest $request, mixed $id): Renderable
    {
        $locale = $request->getCrudLocale();
        $crud   = $this->crud();
        $model  = $crud->model($id);

        /** @var Table $table */
        $table = resolve(Table::class, ['crud' => $crud]);

        $crud->show($table);

        if (0 === count($table->getColumns())) {
            $crud->index($table);
        }

        return view('dashboard::crud.view', [
            'pageTitle'  => __('Trashed :model', ['model' => $crud->plural()]),
            'model'      => $model,
            'table'      => $table,
            'crudLocale' => $locale,
        ]);
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
