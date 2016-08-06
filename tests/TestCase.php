<?php

namespace Humweb\Sociable\Tests;

use Humweb\Sociable\Models\SocialConnection;
use Humweb\Sociable\ServiceProvider;
use Humweb\Sociable\Tests\Stubs\User;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            SocialiteServiceProvider::class
        ];
    }


    protected function getPackageAliases($app)
    {
        return [
            'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        ];
    }


    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('sociable.social.auth_provider', 'laravel');

        $app['config']->set('services.github', [
            'client_id'     => 'id-123',
            'client_secret' => 'secret-123',
            'redirect'      => 'http://localhost/social/handle/github',
        ]);

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }


    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        SocialConnection::setUsersModel(User::class);

        $this->withFactories(__DIR__.'/../database/factories');

        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->artisan('db:seed', [
            '--database' => 'testing',
            '--class'    => 'SociableDatabaseSeeder'
        ]);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }


    public function getMigrationsPath()
    {
        return realpath(__DIR__.'/../database/migrations');
    }
}