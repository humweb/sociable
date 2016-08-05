<?php

namespace Humweb\Sociable;

use Humweb\Sociable\Auth\Manager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * ServiceProvider
 *
 * @package Humweb\Content
 */
class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('social.auth', function ($app) {
            return new Manager($app);
        });
    }

}