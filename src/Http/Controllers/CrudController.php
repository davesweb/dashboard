<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Form;
use Illuminate\Http\RedirectResponse;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\Dashboard\Services\CrudFinder;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Services\StoreCrudService;
use Davesweb\Dashboard\Builders\IndexQueryBuilder;
use Davesweb\Dashboard\Services\UpdateCrudService;
use Davesweb\Dashboard\Services\DestroyCrudService;
use Davesweb\Dashboard\Http\Requests\ShowCrudRequest;
use Davesweb\Dashboard\Http\Requests\IndexCrudRequest;
use Davesweb\Dashboard\Http\Requests\StoreCrudRequest;
use Davesweb\Dashboard\Http\Requests\UpdateCrudRequest;
use Davesweb\Dashboard\Http\Resources\CrudShowResource;
use Davesweb\Dashboard\Http\Requests\DestroyCrudRequest;
use Davesweb\Dashboard\Http\Resources\CrudIndexResource;

class CrudController extends Controller
{
    private CrudFinder $crudFinder;

    public function __construct(CrudFinder $crudFinder)
    {
        $this->crudFinder = $crudFinder;
    }

    public function index(IndexCrudRequest $request, IndexQueryBuilder $builder): Renderable|CrudIndexResource
    {
        $this->authorize('viewAny', get_class($this->crud()->model()));

        $crud  = $this->crud();
        $table = $this->table($crud);

        $crud->index($table);

        // @todo this should be done on the future table builder
        if ($crud->hasAction(Crud::ACTION_EXPORT)) {
            $table->exports();
        }

        $items = $builder->paginate($crud, $request, $table, false, $request->getPerPage($crud->model()));

        return $request->wantsJson() ? new CrudIndexResource($items) : view('dashboard::crud.index', [
            'pageTitle'   => $crud->plural(),
            'items'       => $items,
            'table'       => $table,
            'crudLocale'  => $request->getCrudLocale(),
            'searchQuery' => $request->getSearchQuery(),
            'pageActions' => $crud->getPageActions(),
            'perPage'     => $request->getPerPage($crud->model()),
        ]);
    }

    public function trashed(IndexCrudRequest $request, IndexQueryBuilder $builder): Renderable|CrudIndexResource
    {
        $this->authorize('viewTrashed', get_class($this->crud()->model()));

        $crud  = $this->crud();
        $table = $this->table($crud);

        $crud->trashed($table);

        if (0 === count($table->getColumns())) {
            $crud->index($table);
        }

        // @todo this should be done on the future table builder
        if ($crud->hasAction(Crud::ACTION_EXPORT)) {
            $table->exports();
        }

        $items = $builder->paginate($crud, $request, $table, true, $request->getPerPage($crud->model()));

        return $request->wantsJson() ? new CrudIndexResource($items) : view('dashboard::crud.index', [
            'pageTitle'   => __('Trashed :models', ['models' => $crud->plural()]),
            'items'       => $items,
            'table'       => $table,
            'crudLocale'  => $request->getCrudLocale(),
            'searchQuery' => $request->getSearchQuery(),
            'pageActions' => $crud->getPageActions(),
            'perPage'     => $request->getPerPage($crud->model()),
        ]);
    }

    public function export(IndexCrudRequest $request, IndexQueryBuilder $builder, string $type): Response
    {
        // @todo
        return response();
    }

    public function show(ShowCrudRequest $request, mixed $id): Renderable|CrudShowResource
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

        return $request->wantsJson() ? new CrudShowResource($model) : view('dashboard::crud.view', [
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

    public function store(StoreCrudRequest $request, StoreCrudService $service): RedirectResponse|CrudShowResource
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

        return $request->wantsJson() ? new CrudShowResource($model) : ($request->addAnother() ?
            redirect()->route($crud->getRouteName('create'))->with(['success' => $message]) :
            redirect()->route($crud->getRouteName('edit'), [$model])->with(['success' => $message]));
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

    public function update(UpdateCrudRequest $request, UpdateCrudService $service, mixed $id): RedirectResponse|CrudShowResource
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

        return $request->wantsJson() ? new CrudShowResource($model) : redirect()->back()->with(['success' => $message]);
    }

    public function destroy(DestroyCrudRequest $request, DestroyCrudService $service, mixed $id): RedirectResponse|JsonResponse
    {
        $crud  = $this->crud();
        $model = $crud->model($id);

        $this->authorize('destroy', $model);

        $service->setBeforeCallback($crud->beforeDestroy());
        $service->setAfterCallback($crud->afterDestroy());

        $service->destroy($request, $model);

        $message = $crud->destroyedMessage($model) ?? __('The :model was deleted successfully.', ['model' => $crud->singular()]);

        return $request->wantsJson() ? response()->json(['success' => $message]) : redirect()->back()->with(['success' => $message]);
    }

    public function destroyTrashed(mixed $id): RedirectResponse|JsonResponse
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

    protected function table(Crud $crud): Table
    {
        /** @var Table $table */
        $table = resolve(Table::class, ['crud' => $crud]);

        return $table;
    }
}
