<?php

namespace App\Core;

use App\Core\Auth;
use App\Core\Input;
use App\Models\Users;
use App\Core\Validation;

/**
 * Request
 */
class Request extends Input
{
    public function validate($rules = [], $messages = [])
    {
        $validator = new Validation($rules, $this->all());
        $validator->validate($messages);

        if ($validator->validated()) {
            return true;
        }
        return $validator->getErrors();
    }

    /**
     * Call method of the instance
     * @param  string $method 
     * @param  mixed $args   
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($method === 'user') {
            $user = Auth::user();
            return $user;
        }
        parent::__call($method, $args);
    }
}
