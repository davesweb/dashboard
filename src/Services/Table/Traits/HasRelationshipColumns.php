<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Traits;

use Closure;
use Illuminate\Support\Collection;
use Davesweb\Dashboard\Services\Table\Columns\Column;
use Davesweb\Dashboard\Services\Table\Columns\HasOneColumn;
use Davesweb\Dashboard\Services\Table\Columns\RelationColumn;
use Davesweb\Dashboard\Services\Table\Columns\BelongsToOneColumn;

/**
 * @property iterable $columns
 */
trait HasRelationshipColumns
{
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
}
