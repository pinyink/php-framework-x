<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CrudDatatableController
{
    private $userModel;

    function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function __invoke(ServerRequestInterface $request) : Response
    {
        $output = [
            "draw" => 0,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [
                ['username', 'level', date('Y-m-d H:i:s')]
            ]
        ];
        return Response::json(
            $output
        );
    }
}