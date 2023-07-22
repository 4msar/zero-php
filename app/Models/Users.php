<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Traits\UseObject;


/**
 * Users Model
 */
class Users extends Model
{
    use UseObject;

    protected $table = "users";

    protected $columns = ['id', 'name', 'email', 'password'];
}
