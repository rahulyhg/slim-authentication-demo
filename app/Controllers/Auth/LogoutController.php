<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 04:58
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class LogoutController extends Controller
{
    public function index(Request $request, Response $response)
    {
        if ($request->isPost())
            $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }
}