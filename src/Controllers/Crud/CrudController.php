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
        return Response::html($response);

        // $string = "select user_user, user_level from users";
        // $strPost = strpos($string, 'from users');
        // $str = substr($string, 0, $strPost);
        // // return Response::html($strPost);
        // print_r($str);
        // // return Response::html('');
    }
}