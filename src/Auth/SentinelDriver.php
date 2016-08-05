<?php

namespace Humweb\Sociable\Auth;

use LGL\Core\Auth\Laravel\Facades\Sentinel;

/**
 * SentinelDriver
 *
 * @package ${NAMESPACE}
 */
class SentinelDriver extends AbstractDriver
{

    public function login($userId)
    {
        return $this->app['sentinel']->loginByUserId($userId);
    }


    public function user()
    {
        return $this->app['sentinel']->getUser();
    }

}