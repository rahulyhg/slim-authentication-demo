<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 03:48
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
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
        $email = $post['email'];
        $password = $post['password'];

        $user = $this->auth->validate($email, $password);

        if (! $user) {
            $_SESSION['errors'] = ['email' => array('Invalid email or password.')];
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }

        if ($user->user()->two_step_enabled) {
            $_SESSION['temp']['two_step'] = true;
            $_SESSION['temp']['user_id'] = $user->user()->id;
            return $response->withRedirect($this->router->pathFor('two_factor_step'));
        }

        $user->login();

        return $response->withRedirect($this->router->pathFor('home'));
    }
}