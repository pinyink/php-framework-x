<?php

namespace Acme\Todo\Controllers\Crud;

use Acme\Todo\Core\Datatable;
use Acme\Todo\Models\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CrudDatatableController
{
    private $userModel, $datatable;

    function __construct(UserModel $userModel, Datatable $datatable)
    {
        $this->userModel = $userModel;
        $this->datatable = $datatable;
    }

    public function __invoke(ServerRequestInterface $request) : Response
    {
        $params = $request->getParsedBody() ?? array();

        // load datatable set columnsearch and columnorder
        $this->datatable->setColumnSearch(['user_user', 'user_level']);
        $this->datatable->setColumnOrder(['user_user', 'user_level']);

        $this->datatable->setTable('users');
        $this->datatable->setQueryDatatable("select * from users");
        $this->datatable->setWhere(['user_level' => 'user']);
        // execution datatable
        $queryResult = $this->datatable->datatable($params);

        $data = [];
        foreach ($queryResult['data'] as $key => $value) {
            $row = array();
            $row[] = $value['user_user'];
            $row[] = $value['user_level'];
            $row[] = $value['user_updated_at'];
            $row[] = "<button class='btn btn-xs btn-primary' onclick='edit_data(".$value['user_id'].")'>Edit</button>";
            $data[] = $row;
        }
        $output = [
            "draw" => 0,
            "recordsTotal" => $queryResult['count'],
            "recordsFiltered" => $queryResult['filter'],
            "data" => $data,
        ];
        return Response::json(
            $output
        );
    }
}