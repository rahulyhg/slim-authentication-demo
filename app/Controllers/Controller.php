<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 19:23
 */

namespace App\Controllers;


use Slim\Container;

class Controller
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }
}