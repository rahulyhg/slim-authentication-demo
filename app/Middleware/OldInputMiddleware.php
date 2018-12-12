<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 01:44
 */

namespace App\Middleware;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class OldInputMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (isset($_SESSION['old'])) {
            $this->container->view->getEnvironment()
                ->addGlobal('old', $_SESSION['old']);
        }
        $_SESSION['old'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
    }
}