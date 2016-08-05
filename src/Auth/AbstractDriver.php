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


    public function __construct($app)
    {
        $this->app = $app;
    }


    abstract function login($id);


    abstract function user();
}