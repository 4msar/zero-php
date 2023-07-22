<?php

namespace App\Core;

use App\Core\Session;

/**
 * Base Controller
 */
abstract class Controller
{
    function __construct()
    {
        // just for setup | do not remove
    }

    public function addFlash($name, $value = "")
    {
        Session::addFlash($name, $value);
        return $this;
    }

    public function back($alternative = '/')
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
            if ($previous) {
                return redirectTo($previous);
            }
        }
        return redirectTo($alternative);;
    }
}
