<?php

namespace Acme\Todo\Controllers;

use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class UserController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }
    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $query = $this->userModel->findAll();
        if ($query === null) {
            return Response::plaintext(
                "User not found\n"
            )->withStatus(Response::STATUS_NOT_FOUND);
        }

        $data = json_encode($query);
        return Response::html(
            $data
        );
    }
}
