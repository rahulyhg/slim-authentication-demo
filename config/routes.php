<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Routes

$app->get('/', '\App\Controllers\HomeController:index')
    ->setName('home');


$app->group('', function () {

    $this->get('/register', '\App\Controllers\Auth\RegisterController:index')
        ->setName('auth.register');
    $this->post('/register', '\App\Controllers\Auth\RegisterController:register');

    $this->get('/login', '\App\Controllers\Auth\LoginController:index')
        ->setName('auth.login');
    $this->post('/login', '\App\Controllers\Auth\LoginController:login');

})->add(new \App\Middleware\GuestMiddleware($container));


$app->group('', function () {

    $this->any('/logout', 'App\Controllers\Auth\LogoutController:index')
        ->setName('auth.logout');

})->add(new \App\Middleware\AuthMiddleware($container));
