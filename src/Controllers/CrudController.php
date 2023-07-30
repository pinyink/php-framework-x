<?php

namespace Acme\Todo\Controllers;

use Acme\Todo\Libraries\Tema;
use React\Http\Message\Response;

class CrudController 
{
    public function __invoke()
    {
        $tema = new Tema();
        $response = $tema->render('tema/first.html.twig', ['name' => 'John Doe', 
        'occupation' => 'gardener']);
        return Response::html($response);
    }
}