<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 15-12-2018
 * Time: 17:43
 */

namespace App\Middleware;


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TwoStepCheckMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (! $this->container->auth->check() && isset($_SESSION['temp']['two_step'], $_SESSION['temp']['user_id'])) {

            $response = $next($request, $response);
            return $response;
        }

        return $response->withStatus(404, "Page not found.");
    }
}