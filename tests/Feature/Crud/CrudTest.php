<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Feature\Crud;

use Illuminate\Support\Facades\Route;
use Davesweb\Dashboard\Tests\Feature\CrudTestCase;

/**
 * @internal
 */
class CrudTest extends CrudTestCase
{
    public function test_routes_are_registered(): void
    {
        $this->registerCrud();

        $this->assertTrue(Route::has($this->crud->getRouteName('index')));
        $this->assertTrue(Route::has($this->crud->getRouteName('show')));
        $this->assertTrue(Route::has($this->crud->getRouteName('create')));
        $this->assertTrue(Route::has($this->crud->getRouteName('store')));
        $this->assertTrue(Route::has($this->crud->getRouteName('edit')));
        $this->assertTrue(Route::has($this->crud->getRouteName('update')));
        $this->assertTrue(Route::has($this->crud->getRouteName('destroy')));

        $this->assertFalse(Route::has($this->crud->getRouteName('trashed')));
        $this->assertFalse(Route::has($this->crud->getRouteName('destroy-hard')));
    }

    public function test_menu_items_are_registered(): void
    {
        $this->registerCrud();

        $this->actingAs($this->user, 'dashboard');

        $response = $this->get(dashboard_route('index'));

        $response->assertSuccessful();

        $response->assertSee(route($this->crud->getRouteName('index')));
    }
}
