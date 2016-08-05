<?php

namespace Humweb\Sociable\Auth;

/**
 * LaravelDriver
 *
 * @package ${NAMESPACE}
 */
class LaravelDriver extends AbstractDriver
{

    public function login($userId)
    {
        return $this->app['auth']->loginUsingId($userId);
    }


    public function user()
    {
        return $this->app['auth']->user();
    }

}