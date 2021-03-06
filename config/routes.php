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

    $this->get('/settings', 'App\Controllers\User\SettingController:index')
        ->setName('user.settings');
    $this->post('/settings', 'App\Controllers\User\SettingController:update');

    $this->get('/api/q', 'App\Controllers\User\SettingController:getQuestions')
        ->setName('2fa.getQuestions');

    $this->any('/logout', 'App\Controllers\Auth\LogoutController:index')
        ->setName('auth.logout');

})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('/two-step', function () {

    $this->get('', 'App\Controllers\User\TwoStepController:index')
        ->setName('two_factor_step');
    $this->post('', 'App\Controllers\User\TwoStepController:verify');

})->add(new \App\Middleware\TwoStepCheckMiddleware($container));