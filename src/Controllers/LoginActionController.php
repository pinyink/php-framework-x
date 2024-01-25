<?php

namespace Acme\Todo\Controllers;

use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class LoginActionController
{
    private $userModel;

    function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }
    public function __invoke(ServerRequestInterface $request) : Response
    {
        $data = $request->getParsedBody();
        $query = $this->userModel->findAll(['user_user' => $data['username']], 1);
        // return Response::json($query);
        if (empty($query[0])) {
            return Response::html('<h4>Username Not Found</h4>');
        }
        if ($query[0]['user_password'] == null) {
            return Response::html('<h4>Password Not Same</h4>');
        }
        if (!password_verify($data['password'], $query[0]['user_password'])) {
            return Response::html('<h4>Password Not Same</h4>');
        }
        return Response::html('<h4>Ok</h4>');
    }
}