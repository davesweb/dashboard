<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Action;

class ActionCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public static function pageActions(Request $request, array $actions, array $names, string $routeNamePrefix): static
    {
        $collection = new static();

        if (in_array(Crud::ACTION_CREATE, $actions, true) && !$request->routeIs($routeNamePrefix . 'create')) {
            $collection->push(new Action(
                title: __('Create :model', ['model' => $names['singular']]),
                route: $routeNamePrefix . 'create',
                icon: new HtmlString('<i class="fa fa-plus"></i>'),
                ability: 'create',
            ));
        }

        if (in_array(Crud::ACTION_INDEX_TRASHED, $actions, true) && !$request->routeIs($routeNamePrefix . 'trashed')) {
            $collection->push(new Action(
                title: __('View deleted :models', ['models' => $names['plural']]),
                route: $routeNamePrefix . 'trashed',
                icon: new HtmlString('<i class="fa fa-close"></i>'),
                ability: 'viewTrashed',
            ));
        }

        if (in_array(Crud::ACTION_INDEX, $actions, true) && !$request->routeIs($routeNamePrefix . 'index')) {
            $collection->push(new Action(
                title: __('View :models', ['models' => $names['plural']]),
                route: $routeNamePrefix . 'index',
                icon: new HtmlString('<i class="fa fa-eye"></i>'),
                ability: 'viewAny',
            ));
        }

        return $collection;
    }

    public static function tableActions(Request $request, array $actions, array $names, string $routeNamePrefix): static
    {
        $collection = new static();

        if (in_array(Crud::ACTION_SHOW, $actions, true) && !$request->routeIs($routeNamePrefix . 'show')) {
            $collection->push(new Action(
                title: __('View this :model', ['model' => $names['singular']]),
                route: $routeNamePrefix . 'show',
                icon: new HtmlString('<i class="fa fa-eye fa-fw"></i>'),
                ability: 'view',
            ));
        }

        if (in_array(Crud::ACTION_UPDATE, $actions, true)) {
            $collection->push(new Action(
                title: __('Edit this :model', ['model' => $names['singular']]),
                route: $routeNamePrefix . 'edit',
                icon: new HtmlString('<i class="fa fa-pencil fa-fw"></i>'),
                ability: 'update',
            ));
        }

        if (in_array(Crud::ACTION_DESTROY, $actions, true) && !$request->routeIs($routeNamePrefix . 'trashed')) {
            $collection->push(new Action(
                title: __('Delete this :model', ['model' => $names['singular']]),
                route: $routeNamePrefix . 'destroy',
                icon: new HtmlString('<i class="fa fa-close fa-fw"></i>'),
                formMethod: 'delete',
                ability: 'destroy',
            ));
        }

        if (in_array(Crud::ACTION_DESTROY, $actions, true) && $request->routeIs($routeNamePrefix . 'trashed')) {
            $collection->push(new Action(
                title: __('Delete this :model permanently', ['model' => $names['singular']]),
                route: $routeNamePrefix . 'destroy-trashed',
                icon: new HtmlString('<i class="fa fa-close fa-fw"></i>'),
                formMethod: 'delete',
                ability: 'destroyTrashed',
            ));
        }

        return $collection;
    }
}
