<?php

namespace Acme\Todo\Models;

use Acme\Todo\Core\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
}