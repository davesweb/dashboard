<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Traits;

use Closure;
use Illuminate\Support\Collection;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Action;
use Davesweb\Dashboard\Services\Table\Columns\Column;
use Davesweb\Dashboard\Services\Table\Columns\ActionsColumn;

/**
 * @property iterable $columns
 * @property Crud     $crud
 */
trait HasColumns
{
    public function column(string $title, string|Closure|null $content = null, bool $orderable = false, bool $searchable = false, bool $translated = false): Column
    {
        $column = resolve(Column::class);

        $column = $column->title($title)->orderable($orderable)->searchable($searchable)->translated($translated);

        if (null !== $content) {
            $column->content($content);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function actionColumn(array $actions): ActionsColumn
    {
        /** @var ActionsColumn $column */
        $column = resolve(ActionsColumn::class, ['actions' => $actions]);

        $this->columns[] = $column;

        return $column;
    }

    /**
     * @param Action[] $extraActions
     */
    public function defaultActionsColumn(array $extraActions = []): ActionsColumn
    {
        $actions = array_merge($this->crud->getTableActions()->toArray(), $extraActions);

        /** @var ActionsColumn $column */
        $column = resolve(ActionsColumn::class, ['actions' => $actions]);

        $this->columns[] = $column;

        return $column;
    }

    public function getColumns(): iterable
    {
        return $this->columns;
    }

    public function getHeaders(): array
    {
        return collect($this->columns)->map(function (Column $column) {
            return $column->getTitle();
        })->toArray();
    }

    public function getSearchableColumns(): Collection
    {
        $columns = collect();

        foreach ($this->columns as $column) {
            if ($column->isSearchable()) {
                $columns->push($column);
            }
        }

        return $columns;
    }
}
