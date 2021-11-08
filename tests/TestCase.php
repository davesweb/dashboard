<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests;

use Illuminate\Support\Str;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Schema;
use Davesweb\Dashboard\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Davesweb\Dashboard\CrudServiceProvider;
use Davesweb\Dashboard\RouteServiceProvider;
use Davesweb\Dashboard\FortifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
use Davesweb\Dashboard\ModelTranslators\DaveswebTranslator;
use Laravel\Fortify\FortifyServiceProvider as LaravelFortifyServiceProvider;

/**
 * @internal
 */
class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $namespace = '\\Davesweb\\Dashboard\\Tests\\Factories\\';

            $modelName = Str::afterLast($modelName, '\\');

            return $namespace . $modelName . 'Factory';
        });

        $this->setUpDatabase();
    }

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
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.debug', true);

        //$app['config']->set('dashboard.route', 'dashboard');
        //$app['config']->set('dashboard.route-prefix', 'dashboard.');
        $app['config']->set('dashboard.middleware', ['web']);
        $app['config']->set('dashboard.users.table', 'dashboard_users');
        $app['config']->set('dashboard.crud.locations', [__DIR__ . '/Crud' => '\\Davesweb\\Dashboard\\Tests\\Crud']);
        $app['config']->set('dashboard.translator', DaveswebTranslator::class);

        $app['config']->set('fortify.guard', 'dashboard');
        $app['config']->set('fortify.password', 'users');
        $app['config']->set('fortify.username', 'email');
        $app['config']->set('fortify.email', 'email');
        $app['config']->set('fortify.home', '');
        $app['config']->set('fortify.prefix', '');
        $app['config']->set('fortify.middleware', 'web');
        $app['config']->set('fortify.views', false);
        $app['config']->set('fortify.limiters', [
            'login'      => 'login',
            'two-factor' => 'two-factor',
        ]);
        $app['config']->set('fortify.features', [
            //Features::registration(),
            Features::resetPasswords(),
            // Features::emailVerification(),
            Features::updateProfileInformation(),
            Features::updatePasswords(),
            Features::twoFactorAuthentication([
                'confirmPassword' => false,
            ]),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelFortifyServiceProvider::class,
            ServiceProvider::class,
            FortifyServiceProvider::class,
            RouteServiceProvider::class,
            CrudServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }

    protected function setUpDatabase(): void
    {
        Schema::create('crud_models', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->timestamps();
        });
    }
}
