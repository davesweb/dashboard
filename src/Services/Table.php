<?php

namespace Davesweb\Dashboard\Services;

use Closure;
use Davesweb\Dashboard\Services\Table\Action;
use Davesweb\Dashboard\Services\Table\Column;
use Davesweb\Dashboard\Services\Table\ActionsColumn;

class Table
{
    /**
     * @var Column[]
     */
    private array $columns = [];

    private Crud $crud;

    public function __construct(Crud $crud)
    {
        $this->crud = $crud;
    }

    public function column(string $title, string|Closure|null $content = null, bool $orderable = false, bool $searchable = false, bool $translated = false): Column
    {
        $column = (new Column())->title($title)->orderable($orderable)->searchable($searchable)->translated($translated);

        if (null !== $content) {
            $column->content($content);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function actionColumn(array $actions): ActionsColumn
    {
        $column = new ActionsColumn($actions);

        $this->columns[] = $column;

        return $column;
    }

    /**
     * @param Action[] $extraActions
     */
    public function defaultActionsColumn(array $extraActions = []): ActionsColumn
    {
        $actions = array_merge($this->crud->getTableActions(), $extraActions);

        $column = new ActionsColumn($actions);

        $this->columns[] = $column;

        return $column;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getHeaders(): array
    {
        return collect($this->columns)->map(function (Column $column) {
            return $column->getTitle();
        })->toArray();
    }

    public function hasSearch(): bool
    {
        foreach ($this->columns as $column) {
            if ($column->isSearchable()) {
                return true;
            }
        }

        return false;
    }

    public function hasTranslations(): bool
    {
        foreach ($this->columns as $column) {
            if ($column->isTranslated()) {
                return true;
            }
        }

        return false;
    }
}
