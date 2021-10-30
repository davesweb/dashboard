<?php

namespace Davesweb\Dashboard\Services;

use Closure;
use Davesweb\Dashboard\Services\Table\Column;

class Table
{
    private array $columns = [];

    public function column(string $title, string|Closure|null $content = null, bool $orderable = false, bool $searchable = false, bool $translated = false): Column
    {
        $column = (new Column())->title($title)->orderable($orderable)->searchable($searchable)->translated($translated);

        if (null !== $content) {
            $column->content($content);
        }

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
}
