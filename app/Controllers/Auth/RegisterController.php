<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 21:09
 */

namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RegisterController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/register.twig');
    }

    public function register(Request $request, Response $response)
    {
        $post = $request->getParsedBody();

        $validator = $this->validator->make($post, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors()->messages();
            return $response->withRedirect($this->router->pathFor('auth.register'));
        }

        User::create([
            'name' => ucwords(
                preg_replace('/\s+/', ' ', $post['name'])
            ),
            'email' => $post['email'],
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
        ]);

        $auth = $this->auth->attempt($post['email'], $post['password']);

        if (! $auth) {
            $this->flash->addMessage('error', 'Something went wrong while authenticating.');
            return $response->withRedirect($this->router->pathFor('auth.register'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }
}