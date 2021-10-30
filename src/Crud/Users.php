<?php

namespace Davesweb\Dashboard\Crud;

use Davesweb\Dashboard\Models\User;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;

class Users extends Crud
{
    protected string $model = User::class;

    public function index(Table $table): void
    {
        $table->column('#')->orderable()->content('id');
        $table->column(__('Name'))->orderable()->searchable();
        $table->column(__('Email address'))->orderable()->searchable()->content('email');
    }
}
