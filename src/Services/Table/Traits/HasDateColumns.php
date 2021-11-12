<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Traits;

use Closure;
use Davesweb\Dashboard\Services\Table\Columns\DateColumn;
use Davesweb\Dashboard\Services\Table\Columns\DateTimeColumn;
use Davesweb\Dashboard\Services\Table\Columns\CreatedAtColumn;
use Davesweb\Dashboard\Services\Table\Columns\DeletedAtColumn;
use Davesweb\Dashboard\Services\Table\Columns\UpdatedAtColumn;

/**
 * @property iterable $columns
 */
trait HasDateColumns
{
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
}
