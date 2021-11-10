<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Events;

use Illuminate\Database\Eloquent\Model;

class DestroyedCrud
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
