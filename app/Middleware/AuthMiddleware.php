<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 05:36
 */

namespace App\Middleware;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (! $this->container->auth->check()) {
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}