<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

class RelationColumn extends Column
{
    private string $relation;

    public function relation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }
}
