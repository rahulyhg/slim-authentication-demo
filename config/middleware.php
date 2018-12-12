<?php
// Application middleware

// Click-jacking Protection Middleware
$app->add(new Clickjacking\Middleware\XFrameOptions);

// Session in database
//$app->add(new App\Middleware\SessionMiddleware($container->get('session')));

$app->add(new App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));