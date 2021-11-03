<?php

namespace Davesweb\Dashboard\Crud;

use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Models\User;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;

class Users extends Crud
{
    protected string $model = User::class;

    public function icon(): HtmlString
    {
        return new HtmlString('<i class="fa fa-users"></i>');
    }

    public function index(Table $table): void
    {
        $table->column('#')->orderable()->content('id');
        $table->column(__('Name'))->orderable()->searchable(searchField: 'name');
        $table->column(__('Email address'))->orderable()->searchable(searchField: 'email')->content('email');
        $table->defaultActionsColumn();
    }
}
