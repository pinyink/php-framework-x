<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Libraries\Tema;
use Acme\Todo\Models\UserModel;
use React\Http\Message\Response;

class CrudController 
{
    private $userModel;

    function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function __invoke()
    {
        $tema = new Tema();
        $response = $tema->render('crud/view.html.twig', ['name' => 'John Doe', 
        'occupation' => 'gardener']);

        setcookie('cobacookie', 'wkwkwkwkw', time() + 20);
        return Response::html($response)->withHeader('Cache-Control', 'max-age=3600');
    }
}