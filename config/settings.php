<?php

return [
    'settings' => [
        'displayErrorDetails' => $_ENV['APP_ENV'] === 'dev',
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/../resources/views',
            'cache_path' => $_ENV['APP_ENV'] === 'dev' ? false : __DIR__ . '/../cache', // Do not cache views in development.
        ],

        // Monolog settings
        'logger' => [
            'name' => 'authentication-demo',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'determineRouteBeforeAppMiddleware' => false,

        'db' => [
            'driver' => env('DB_DRIVER', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_NAME', 'dummy'),
            'username' => env('DB_USER', 'dummy'),
            'password' => env('DB_PASS', 'dummy'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],

//        'session' => [
//            'lifetime' => 120,
//            'expire_on_close' => false,
//            'lottery' => array(2, 100),
//            'cookie' => '_token',
//            'path' => '/',
//            'domain' => null,
//            'driver' => 'database',
//            'table' => 'sessions',
//            'http_only' => true,
//        ],
    ],
];
