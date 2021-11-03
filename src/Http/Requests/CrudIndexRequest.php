<?php

namespace Davesweb\Dashboard\Http\Requests;

class CrudIndexRequest extends CrudRequest
{
    public function hasSearch(): bool
    {
        return $this->has('q');
    }

    public function getSearchQuery(): string
    {
        return $this->get('q');
    }
}
