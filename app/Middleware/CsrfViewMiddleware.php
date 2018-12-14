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

        $tokenName = $this->container->csrf->getTokenName();
        $tokenValue = $this->container->csrf->getTokenValue();

        $this->container->view->getEnvironment()
            ->addGlobal('csrf_name_key', $nameKey);
        $this->container->view->getEnvironment()
            ->addGlobal('csrf_value_key', $valueKey);

        $this->container->view->getEnvironment()
            ->addGlobal('csrf_token_name', $tokenName);
        $this->container->view->getEnvironment()
            ->addGlobal('csrf_token_value', $tokenValue);

        $this->container->view->getEnvironment()
            ->addGlobal('csrf', [
                'field' => '
                    <input type="hidden" name="' . $nameKey .'" value="' . $tokenName .'">
                    <input type="hidden" name="' . $valueKey .'" value="' . $tokenValue .'">
                ',
            ]);

        $response = $next($request, $response);
        return $response;
    }
}