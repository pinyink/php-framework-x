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
        $log['message'] = "Username failed to save";
        $set = [
            'user_user' => $username,
            'user_level' => $data['level'],
            'user_updated_at' => date('Y-m-d H:i:s')
        ];
        if ($data['method'] == 'add') {
            if ($query == 0) {
                $qInsert = $this->userModel->insert($set);
                $log['message'] = $qInsert >= 1 ? 'save success' : 'failed to save';
            } else {
                $log['message'] = "Username Exist";
            }
        } else {
            $qInsert = $this->userModel->update($data['id'], $set);
            $log['message'] = $qInsert >= 1 ? 'save success' : 'failed to save';
        }
        return Response::json(
            $log
        );
    }
}