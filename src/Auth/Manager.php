<?php

namespace Humweb\Sociable\Auth;

use Illuminate\Support\Manager as BaseManager;

/**
 * Manager
 *
 * @package Humweb\SociableConnection\Auth
 */
class Manager extends BaseManager
{

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