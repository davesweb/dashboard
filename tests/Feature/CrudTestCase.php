<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Feature;

use Davesweb\Dashboard\Models\User;
use Davesweb\Dashboard\Tests\TestCase;
use Illuminate\Contracts\Hashing\Hasher;
use Davesweb\Dashboard\Tests\Crud\CrudModel;
use Davesweb\Dashboard\Layout\Sidebar\Sidebar;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @internal
 */
class CrudTestCase extends TestCase
{
    use RefreshDatabase;

    protected CrudModel $crud;

    protected User $user;

    protected function registerCrud(): void
    {
        $this->crud = resolve(CrudModel::class);

        $this->crud->registerRouters(resolve('router'));
        $this->crud->registerMenu(Sidebar::factory());

        $this->user = User::factory()->create([
            'email'    => 'test@user.com',
            'password' => resolve(Hasher::class)->make('password'),
        ]);
    }
}
