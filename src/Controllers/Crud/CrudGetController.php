<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CrudGetController
{
    private $userModel;

    function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $query = $this->userModel->find($id);
        if ($query === null) {
            return Response::plaintext(
                "User not found\n"
            )->withStatus(Response::STATUS_NOT_FOUND);
        }
        return Response::json($query);
    }
}