<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Form;
use Illuminate\Http\RedirectResponse;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\Dashboard\Services\CrudFinder;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Services\StoreCrudService;
use Davesweb\Dashboard\Services\UpdateCrudService;
use Davesweb\Dashboard\Services\DestroyCrudService;
use Davesweb\Dashboard\Http\Requests\ShowCrudRequest;
use Davesweb\Dashboard\Http\Requests\IndexCrudRequest;
use Davesweb\Dashboard\Http\Requests\StoreCrudRequest;
use Davesweb\Dashboard\Http\Requests\UpdateCrudRequest;
use Davesweb\Dashboard\Http\Requests\DestroyCrudRequest;

class CrudController extends Controller
{
    private CrudFinder $crudFinder;

    public function __construct(CrudFinder $crudFinder)
    {
        $this->crudFinder = $crudFinder;
    }

    public function index(IndexCrudRequest $request): Renderable
    {
        $this->authorize('viewAny', get_class($this->crud()->model()));

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

    public function trashed(IndexCrudRequest $request): Renderable
    {
        $this->authorize('viewTrashed', get_class($this->crud()->model()));

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

    public function show(ShowCrudRequest $request, mixed $id): Renderable
    {
        $locale = $request->getCrudLocale();
        $crud   = $this->crud();
        $model  = $crud->model($id);

        // todo: should the 404 or the 403 come first?
        $this->authorize('view', $model);

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

    public function create(ShowCrudRequest $request): Renderable
    {
        $this->authorize('create', get_class($this->crud()->model()));

        $locale = $request->getCrudLocale();
        $crud   = $this->crud();
        $model  = $crud->model();

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);
        $form->route($crud->getRouteName('store'));

        $crud->create($form, $model);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form, $model);
        }

        return view('dashboard::crud.create', [
            'pageTitle'  => __('Create :model', ['model' => $crud->singular()]),
            'form'       => $form,
            'crud'       => $crud,
            'formLocale' => $locale,
            'model'      => $model,
        ]);
    }

    public function store(StoreCrudRequest $request, StoreCrudService $service): RedirectResponse
    {
        $this->authorize('create', get_class($this->crud()->model()));

        $crud  = $this->crud();
        $model = $crud->model();

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);

        $crud->create($form, $model);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form, $model);
        }

        $request->validate($form->getValidationRules());

        $service->setBeforeCallback($crud->beforeStore());
        $service->setAfterCallback($crud->afterStore());

        $model = $service->store($request, $crud->model());

        $message = $crud->storedMessage($model) ?? __('The :model was created successfully.', ['model' => $crud->singular()]);

        return $request->addAnother() ?
            redirect()->route($crud->getRouteName('create'))->with(['success' => $message]) :
            redirect()->route($crud->getRouteName('edit'), [$model])->with(['success' => $message]); // todo Created model ID
    }

    public function edit(ShowCrudRequest $request, mixed $id): Renderable
    {
        $locale = $request->getCrudLocale();
        $crud   = $this->crud();
        $model  = $crud->model($id);

        $this->authorize('update', $model);

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

    public function update(UpdateCrudRequest $request, UpdateCrudService $service, mixed $id): RedirectResponse
    {
        $crud  = $this->crud();
        $model = $crud->model($id);

        $this->authorize('update', $model);

        /** @var Form $form */
        $form = resolve(Form::class, ['crud' => $crud]);
        $form->method('put');

        $crud->edit($form, $model);

        if (!$form->hasSectionsOrFields()) {
            $crud->form($form, $model);
        }

        $request->validate($form->getValidationRules());

        $service->setBeforeCallback($crud->beforeUpdate());
        $service->setAfterCallback($crud->afterUpdate());

        $model = $service->update($request, $model);

        $message = $crud->updatedMessage($model) ?? __('The :model was updated successfully.', ['model' => $crud->singular()]);

        return redirect()->back()->with(['success' => $message]);
    }

    public function destroy(DestroyCrudRequest $request, DestroyCrudService $service, mixed $id): RedirectResponse
    {
        $crud  = $this->crud();
        $model = $crud->model($id);

        $this->authorize('destroy', $model);

        $service->setBeforeCallback($crud->beforeDestroy());
        $service->setAfterCallback($crud->afterDestroy());

        $service->destroy($request, $model);

        $message = $crud->destroyedMessage($model) ?? __('The :model was deleted successfully.', ['model' => $crud->singular()]);

        return redirect()->back()->with(['success' => $message]);
    }

    public function destroyTrashed(mixed $id): RedirectResponse
    {
        $crud  = $this->crud();
        $model = $crud->model($id);

        $this->authorize('destroyTrashed', $model);
    }

    protected function crud(): Crud
    {
        return $this->crudFinder->findCrudByRequest(request());
    }

    protected function query(): Builder
    {
        return $this->crud()->query();
    }
}
