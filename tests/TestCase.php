<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests;

use Davesweb\Dashboard\ServiceProvider;
use Davesweb\Dashboard\CrudServiceProvider;
use Davesweb\Dashboard\RouteServiceProvider;
use Davesweb\Dashboard\FortifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * @internal
 */
class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            FortifyServiceProvider::class,
            RouteServiceProvider::class,
            CrudServiceProvider::class,
        ];
    }
}
