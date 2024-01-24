<?php

namespace Acme\Todo\Controllers;

use Acme\Todo\Libraries\Tema;
use React\Http\Message\Response;

class LoginController 
{
    function __invoke() : Response
    {
        $tema = new Tema();
        $view = $tema->render('login.html.twig', []);
        return Response::html($view);
    }
}