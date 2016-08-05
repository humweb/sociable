<?php

namespace Humweb\Sociable\Auth;

/**
 * AbstractDriver
 *
 * @package Humweb\SociableConnection\Auth
 */
abstract class AbstractDriver
{

    protected $app;


    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    abstract function login($id);


    abstract function user();
}