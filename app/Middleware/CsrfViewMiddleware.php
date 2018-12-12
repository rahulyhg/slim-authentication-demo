<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 03:25
 */

namespace App\Middleware;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CsrfViewMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();

        $this->container->view->getEnvironment()
            ->addGlobal('csrf', [
                'field' => '
                    <input type="hidden" name="' . $nameKey .'" value="' . $this->container->csrf->getTokenName() .'">
                    <input type="hidden" name="' . $valueKey .'" value="' . $this->container->csrf->getTokenValue() .'">
                ',
            ]);

        $response = $next($request, $response);
        return $response;
    }
}