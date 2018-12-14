<?php
// DIC configuration

$container = $app->getContainer();

// Eloquent model
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

// Session in database. This worked like a charm, but slim-csrf and flash packages broke after this.
// Since I was feeling too lazy, I switched back to our favorite $_SESSION PHP super-global.
//
//$container['session'] = function ($container) {
//    $sessionContainer = new Illuminate\Container\Container;
//    $sessionContainer['db'] = $container->get('db');
//
//    $sessionContainer['config'] = new Illuminate\Config\Repository();
//    $sessionContainer['config']['session'] = $container->get('settings')['session'];
//
//    $sessionManager = new \Illuminate\Session\SessionManager($sessionContainer);
//    $sessionContainer['session.store'] = $sessionManager->driver();
//    $sessionContainer['session'] = $sessionManager;
//
//    return $sessionContainer['session'];
//};

// view renderer
$container['view'] = function ($container) {
    $settings = $container->get('settings')['view'];

    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => $settings['cache_path'],
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    $view->getEnvironment()->addGlobal("current_path", $container->get('request')->getUri()->getPath());

    return $view;
};

// Monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Validator
$container['validator'] = function ($container) use ($capsule) {
    $validator = new App\Validator\Validator;
    $validator->setPresenceVerifier($capsule->getDatabaseManager());

    return $validator;
};

// Flash Messages
$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

// CSRF Guard
$container['csrf'] = function ($container) {
    $guard = new \Slim\Csrf\Guard;
    $guard->setPersistentTokenMode(true);

    return $guard;
};

// Auth
$container['auth'] = function ($container) {
    return new \App\Auth;
};

// Controller
$container['App\Controllers\Controller'] = function ($container) {
    return new App\Controllers\Controller($container);
};
