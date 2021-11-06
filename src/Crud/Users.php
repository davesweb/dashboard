<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Crud;

use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Models\User;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Form;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Model;

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

    public function create(Form $form): void
    {
        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email address'))->required()->unique($this->model()->getTable(), 'email');
        $form->password('password', __('Password'))->required()->confirm();
    }

    public function edit(Form $form, Model $model): void
    {
        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email address'))->required()->unique($model->getTable(), 'email', $model->getKey());
    }
}
