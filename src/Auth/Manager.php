<?php

namespace Humweb\Sociable\Auth;

use Illuminate\Container\Container;
use Illuminate\Support\Manager as BaseManager;

/**
 * Manager
 *
 * @package Humweb\SociableConnection\Auth
 */
class Manager extends BaseManager
{

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app = null)
    {
        $this->app = $app ?: Container::getInstance();
    }


    /**
     * Create Laravel driver
     *
     * @return \Humweb\Sociable\Auth\LaravelDriver
     */
    protected function createLaravelDriver()
    {
        return new LaravelDriver($this->app);
    }


    /**
     * @return \Humweb\Sociable\Auth\SentinelDriver
     */
    protected function createSentinelDriver()
    {
        return new SentinelDriver($this->app);
    }


    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('social.auth_provider', 'laravel');
    }
}