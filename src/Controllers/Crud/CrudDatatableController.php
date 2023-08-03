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
        $params = $request->getParsedBody();

        // load datatable
        $this->userModel->setColumnSearch(['user_user', 'user_level']);
        $this->userModel->setColumnOrder(['user_user', 'user_level']);
        $this->userModel->setQueryDatatable("select * from users");
        $this->userModel->setFilteredDatatable("select count(*) total from users");
        $queryResult = $this->userModel->datatable($params);

        $data = [];
        foreach ($queryResult['data'] as $key => $value) {
            $row = array();
            $row[] = $value['user_user'];
            $row[] = $value['user_level'];
            $row[] = $value['user_updated_at'];
            $data[] = $row;
        }
        $output = [
            "draw" => 0,
            "recordsTotal" =>$queryResult['count'],
            "recordsFiltered" => $queryResult['filter'],
            "data" => $data,
        ];
        return Response::json(
            $output
        );
    }
}