<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Crud;

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;
use Davesweb\Dashboard\Tests\Models\CrudModel as Model;

class CrudModel extends Crud
{
    protected string $model = Model::class;

    public function index(Table $table): void
    {
        $table->column('ID', 'id', true, false, false);
        $table->column('title', 'title', true, true, false);
    }
}
