<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 22:40
 */

namespace App\Middleware;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (isset($_SESSION['errors'])) {
            $this->container->view->getEnvironment()
                ->addGlobal('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }

        $response = $next($request, $response);
        return $response;
    }
}