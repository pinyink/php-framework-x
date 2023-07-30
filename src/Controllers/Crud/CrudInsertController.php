<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Libraries\Tema;
use React\Http\Message\Response;

class CrudInsertController 
{
    public function __invoke()
    {
        $tema = new Tema();
        $response = $tema->render('crud/view.html.twig', ['name' => 'John Doe', 
        'occupation' => 'gardener']);
        return Response::html($response);
    }
}