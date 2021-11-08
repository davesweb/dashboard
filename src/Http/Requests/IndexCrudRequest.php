<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Requests;

class IndexCrudRequest extends CrudRequest
{
    public function hasSearch(): bool
    {
        return $this->has('q') && null !== $this->get('q');
    }

    public function getSearchQuery(): ?string
    {
        return $this->get('q');
    }
}
