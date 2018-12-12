<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 03:48
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class LoginController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function login(Request $request, Response $response)
    {
        $post = $request->getParsedBody();

        $auth = $this->auth->attempt(
            $post['email'],
            $post['password']
        );

        if (! $auth) {
            $_SESSION['errors'] = ['email' => array('Invalid email or password.')];
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }
}