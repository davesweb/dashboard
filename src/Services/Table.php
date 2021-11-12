<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Closure;
use Illuminate\Support\Collection;
use Davesweb\Dashboard\Services\Table\Columns\Column;
use Davesweb\Dashboard\Services\Table\Columns\DateColumn;
use Davesweb\Dashboard\Services\Table\Columns\HasOneColumn;
use Davesweb\Dashboard\Services\Table\Columns\ActionsColumn;
use Davesweb\Dashboard\Services\Table\Columns\DateTimeColumn;
use Davesweb\Dashboard\Services\Table\Columns\RelationColumn;
use Davesweb\Dashboard\Services\Table\Columns\CreatedAtColumn;
use Davesweb\Dashboard\Services\Table\Columns\DeletedAtColumn;
use Davesweb\Dashboard\Services\Table\Columns\UpdatedAtColumn;
use Davesweb\Dashboard\Services\Table\Columns\BelongsToOneColumn;

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
        $column = resolve(Column::class);

        $column = $column->title($title)->orderable($orderable)->searchable($searchable)->translated($translated);

        if (null !== $content) {
            $column->content($content);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function date(string $title, string|Closure|null $content = null, bool $orderable = false, ?string $format = null): DateColumn
    {
        $column = resolve(DateColumn::class);

        $column = $column->title($title)->orderable($orderable);

        if (null !== $content) {
            $column->content($content);
        }

        if (null !== $format) {
            $column->format($format);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function datetime(string $title, string|Closure|null $content = null, bool $orderable = false, ?string $format = null): DateTimeColumn
    {
        $column = resolve(DateTimeColumn::class);

        $column = $column->title($title)->orderable($orderable);

        if (null !== $content) {
            $column->content($content);
        }

        if (null !== $format) {
            $column->format($format);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function createdAt(string $title, bool $orderable = false, ?string $format = null): CreatedAtColumn
    {
        $column = resolve(CreatedAtColumn::class);

        $column = $column->title($title)->orderable($orderable);

        if (null !== $format) {
            $column->format($format);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function updatedAt(string $title, bool $orderable = false, ?string $format = null): UpdatedAtColumn
    {
        $column = resolve(UpdatedAtColumn::class);

        $column = $column->title($title)->orderable($orderable);

        if (null !== $format) {
            $column->format($format);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function deletedAt(string $title, bool $orderable = false, ?string $format = null): DeletedAtColumn
    {
        $column = resolve(DeletedAtColumn::class);

        $column = $column->title($title)->orderable($orderable);

        if (null !== $format) {
            $column->format($format);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function hasOne(string $title, string $relation, string|Closure|null $content = null, bool $orderable = false, bool $searchable = false, bool $translated = false): HasOneColumn
    {
        /** @var HasOneColumn $column */
        $column = resolve(HasOneColumn::class);

        $column = $column->title($title)->relation($relation)->orderable($orderable)->searchable($searchable)->translated($translated);

        if (null !== $content) {
            $column->content($content);
        }

        $this->columns[] = $column;

        return $column;
    }

    public function belongsToOne(string $title, string $relation, string|Closure|null $content = null, bool $orderable = false, bool $searchable = false, bool $translated = false): BelongsToOneColumn
    {
        /** @var BelongsToOneColumn $column */
        $column = resolve(BelongsToOneColumn::class);

        $column = $column->title($title)->relation($relation)->orderable($orderable)->searchable($searchable)->translated($translated);

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

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getRelationshipColumns(): Collection
    {
        return collect($this->columns)->reject(function (Column $column) {
            return !$column instanceof RelationColumn;
        });
    }

    public function getRelationshipNames(): iterable
    {
        return $this->getRelationshipColumns()->map(function (RelationColumn $column) {
            return $column->getRelation();
        })->toArray();
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
