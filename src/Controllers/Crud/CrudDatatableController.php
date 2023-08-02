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
        
        $coloumSearch = ['user_user', 'user_level'];
        $coloumOrder = ['user_user', 'user_level'];

        $query = 'select * from users';
        $queryFiltered = 'select count(*) total from users';
        $queryCount = 'select count(*) total from users';

        // if search active
        $paramValue = [];
        foreach ($coloumSearch as $kSearch => $vSearch) {
            if (isset($params['search']['value'])) {
                $query .= " WHERE 1=1 ";
                $queryFiltered .= " WHERE 1=1 ";
                foreach ($params['search']['value'] as $key => $value) {
                    $query .= "AND {$key} like '%?%' ";
                    $queryFiltered .= "AND {$key} like '%?%' ";
                    array_push($paramValue, $value);
                }
            }
        }
        if (isset($params['order'])) {
            $query .= " order by ".$params['order']['0']['column']." ".$params['order']['0']['dir'];
        }
        if (isset($params['length']) && $params['length'] != -1) {
            $query .= " limit ? offset ?" ;
            array_push($paramValue, $params['length']);
            array_push($paramValue, $params['start']);
        }
        
        $result = $this->userModel->query($query, $paramValue);
        $filter = $this->userModel->query($queryFiltered, $paramValue);
        $count = $this->userModel->query($queryCount, []);
        $data = [];
        foreach ($result as $key => $value) {
            $row = array();
            $row[] = $value['user_user'];
            $row[] = $value['user_level'];
            $row[] = $value['user_updated_at'];
            $data[] = $row;
        }
        $output = [
            "draw" => 0,
            "recordsTotal" =>$count[0]['total'],
            "recordsFiltered" => $filter[0]['total'],
            "data" => $data,
            "req" => $params
        ];
        return Response::json(
            $output
        );
    }
}