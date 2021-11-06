<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Davesweb\Dashboard\Crud\Users;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Form;
use Illuminate\Http\RedirectResponse;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Requests\CrudShowRequest;
use Davesweb\Dashboard\Http\Requests\CrudIndexRequest;
use Davesweb\Dashboard\Http\Requests\StoreCrudRequest;

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
            'pageActions' => $crud->getPageActions(),
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
            'pageTitle'   => __('Trashed :models', ['models' => $crud->plural()]),
            'items'       => $items,
            'table'       => $table,
            'crudLocale'  => $locale,
            'searchQuery' => $request->getSearchQuery(),
            'pageActions' => $crud->getPageActions(),
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
            'pageTitle'  => __(':model', ['model' => $crud->singular()]),
            'model'      => $model,
            'table'      => $table,
            'crudLocale' => $locale,
        ]);
    }

    public function create(CrudShowRequest $request): Renderable
    {
        $locale = $request->getLocale();
        $crud   = $this->crud();

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);
        $form->route($crud->getRouteName('store'));

        $crud->create($form);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form);
        }

        return view('dashboard::crud.create', [
            'pageTitle'  => __('Create :model', ['model' => $crud->singular()]),
            'form'       => $form,
            'crud'       => $crud,
            'formLocale' => $locale,
        ]);
    }

    public function store(StoreCrudRequest $request): RedirectResponse
    {
        $crud  = $this->crud();

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);

        $crud->create($form);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form);
        }

        $request->validate($form->getValidationRules());

        echo 'Storing crud data';

        $message = __('The :model was created successfully.', ['model' => $crud->singular()]);

        return $request->addAnother() ?
            redirect()->route($crud->getRouteName('create'))->with(['success' => $message]) :
            redirect()->route($crud->getRouteName('edit'), [1])->with(['success' => $message]); // todo Created model ID
    }

    public function edit(CrudShowRequest $request, mixed $id): Renderable
    {
        $locale = $request->getLocale();
        $crud   = $this->crud();
        $model  = $crud->model($id);

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);
        $form->method('put');

        $crud->edit($form, $model);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form, $model);
        }

        return view('dashboard::crud.edit', [
            'pageTitle'  => __('Edit :model', ['model' => $crud->singular()]),
            'form'       => $form,
            'crud'       => $crud,
            'model'      => $model,
            'formLocale' => $locale,
        ]);
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
