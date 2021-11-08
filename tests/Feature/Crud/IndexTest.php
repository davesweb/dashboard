<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Feature\Crud;

use Davesweb\Dashboard\Tests\Feature\CrudTestCase;
use Davesweb\Dashboard\Tests\Models\CrudModel as Model;

/**
 * @internal
 */
class IndexTest extends CrudTestCase
{
    public function test_it_shows_items(): void
    {
        $this->registerCrud();

        $this->actingAs($this->user, 'dashboard');

        $items = Model::factory()->count(10)->create();

        $response = $this->get(route($this->crud->getRouteName('index')));

        $response->assertSuccessful();
        $response->assertViewIs('dashboard::crud.index');

        $response->assertSee($items->map(function (Model $model) {
            return $model->title;
        })->toArray());
    }

    public function test_it_shows_search_field(): void
    {
        $this->registerCrud();

        $this->actingAs($this->user, 'dashboard');

        $items = Model::factory()->count(10)->create();

        $response = $this->get(route($this->crud->getRouteName('index')));

        $response->assertSuccessful();

        $response->assertSee('<input type="search" name="q"', false);
    }

    public function test_it_does_not_show_locale_dopdown(): void
    {
        $this->registerCrud();

        $this->actingAs($this->user, 'dashboard');

        $items = Model::factory()->count(10)->create();

        $response = $this->get(route($this->crud->getRouteName('index')));

        $response->assertSuccessful();

        $response->assertDontSee('id="language-select"', false);
    }

    public function test_it_shows_pagination(): void
    {
        $this->registerCrud();

        $this->actingAs($this->user, 'dashboard');

        $items = Model::factory()->count(100)->create();

        $response = $this->get(route($this->crud->getRouteName('index')));

        $response->assertSuccessful();

        $response->assertSee('<ul class="pagination', false);
        $response->assertSee(route($this->crud->getRouteName('index')) . '?page=2', false);
    }

    public function test_it_sorts_records(): void
    {
        // Todo
        self::expectNotToPerformAssertions();
    }

    public function test_search_only_shows_matching_records(): void
    {
        // Todo
        self::expectNotToPerformAssertions();
    }
}
