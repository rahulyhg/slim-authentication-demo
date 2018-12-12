<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 22:37
 */

namespace App\Middleware;


class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}