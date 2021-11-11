<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Crud;

use Closure;
use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Models\User;
use Laravel\Fortify\Rules\Password;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Form;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Http\Requests\CrudRequest;

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

    public function create(Form $form, Model $model): void
    {
        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email address'))->required()->unique($this->model()->getTable(), 'email');
        $form->password('password', __('Password'))->required()->rule(new Password());
    }

    public function edit(Form $form, Model $model): void
    {
        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email address'))->required()->unique($model->getTable(), 'email', $model->getKey());
    }

    public function beforeStore(): ?Closure
    {
        return function (Model $model, CrudRequest $request) {
            $request['password'] = bcrypt($request['password']);
        };
    }
}
