<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 19:14
 */

namespace App\Controllers;


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'home.twig');
    }
}