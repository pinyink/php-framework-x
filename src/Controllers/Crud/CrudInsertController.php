<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CrudInsertController 
{
    private $userModel;

    function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $query = $this->userModel->exists(['user_user' => $username]);
        return Response::json(
            $query
        );
    }
}