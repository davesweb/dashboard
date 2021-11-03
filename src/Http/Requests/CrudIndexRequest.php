<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Requests;

class CrudIndexRequest extends CrudRequest
{
    public function hasSearch(): bool
    {
        return $this->has('q') && $this->get('q') !== null;
    }

    public function getSearchQuery(): ?string
    {
        return $this->get('q');
    }
}
