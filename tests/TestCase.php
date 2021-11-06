<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests;

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
}
