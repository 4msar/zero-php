<?php

namespace App\Core;

use App\Models\Users;

/**
 * Auth
 */
class Auth extends Base
{
    static public $redirectTo = '/login';
    static public $loginSessionName = '__logged__in__';
    static public $user = [];

    /**
     * Check the user is logged in or not
     * @return boolean 
     */
    static public function login($id)
    {
        return Session::set(self::$loginSessionName, $id);
    }

    /**
     * Get the logged in user id
     * @return numeric
     */
    static public function id()
    {
        return Session::get(self::$loginSessionName);
    }

    /**
     * Get the logged in user
     * @return object
     */
    static public function user($column = null)
    {
        if (!self::$user) {
            self::$user = app(Users::class)->find(self::id());
        }
        if ($column) {
            return self::$user->{$column} ?? null;
        }
        return self::$user;
    }

    public function role($name)
    {
        $roles = is_array($name) ? $name : [$name];
        return in_array(self::user('role'), $roles);
    }

    /**
     * Destroy all session
     * @return boolean 
     */
    static public function logout()
    {
        Session::delete(self::$loginSessionName);
    }

    /**
     * Check the user is logged in or not
     * @return boolean 
     */
    static public function check()
    {
        return !empty(Session::get(self::$loginSessionName));
    }

    /**
     * Handle the request
     * @return boolean 
     */
    static public function handle($skip = false)
    {
        if (self::check()) {
            if ($skip) {
                redirectTo('/');
            }
            return true;
        }
        if ($skip) {
            return true;
        }
        redirectTo(self::$redirectTo);
        return false;
    }
}
