<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Requests;

class StoreCrudRequest extends CrudRequest
{
    public function addAnother(): bool
    {
        return $this->has('submit-another');
    }
}
