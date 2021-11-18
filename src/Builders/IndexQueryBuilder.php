<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Builders;

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\Dashboard\Http\Requests\IndexCrudRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IndexQueryBuilder
{
    public function build(Crud $crud, IndexCrudRequest $request, Table $table, bool $onlyTrashed = false): Builder
    {
        $query = $crud->query();
        $query = $this->addEagerLoading($query, $table);
        $query = $this->addTrashedOrNot($query, $onlyTrashed);
        $query = $this->addSearch($query, $crud, $table, $request);
        $query = $this->addOrdering($query, $request);

        return $query;
    }

    public function paginate(Crud $crud, IndexCrudRequest $request, Table $table, bool $onlyTrashed = false, int $perPage = 15): LengthAwarePaginator
    {
        return $this->build($crud, $request, $table, $onlyTrashed)->paginate($perPage);
    }

    private function addEagerLoading(Builder $query, Table $table): Builder
    {
        return $query->with($table->getRelationshipNames());
    }

    private function addTrashedOrNot(Builder $query, bool $onlyTrashed): Builder
    {
        if ($onlyTrashed) {
            $model  = $query->getModel();
            $column = method_exists($model, 'getDeletedAtColumn') ? $model->getDeletedAtColumn() : 'deleted_at';

            $query->whereNotNull($column);
        }

        return $query;
    }

    private function addSearch(Builder $query, Crud $crud, Table $table, IndexCrudRequest $request): Builder
    {
        if ($request->hasSearch()) {
            $crud->search($query, $table->getSearchableColumns(), $request->getCrudLocale(), $request->getSearchQuery());
        }

        return $query;
    }

    private function addOrdering(Builder $builder, IndexCrudRequest $request): Builder
    {
        if (null !== $request->getSortColumn()) {
            $builder->orderBy($request->getSortColumn(), $request->getSortDirection());
        }

        return $builder;
    }
}
