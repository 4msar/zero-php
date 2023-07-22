<?php

/**
 * Get the environment variable
 * from the .env file
 *
 * @param string $name
 * @param string $default
 * @return mixed
 */
function env($name, $default = '')
{
    $env = parse_ini_file(ROOT_DIR . '/.env');
    return $env[strtoupper($name)] ?? $default;
}

/**
 * Create a class object
 * 
 * @param string $className
 * @param mixed $params
 * @return mixed
 */
if (!function_exists('app')) {
    function app($className, ...$params)
    {
        return new $className(...$params);
    }
}

/**
 * The view object
 * 
 * @return \App\Core\View
 */
if (!function_exists('view')) {
    function view(): \App\Core\View
    {
        return app(App\Core\View::class);
    }
}

/**
 * The response object
 * 
 * @param mixed $content
 * @param int $status
 * @return App\Core\Response
 */
if (!function_exists('response')) {
    function response($content = "", $status = 200): App\Core\Response
    {
        return app(App\Core\Response::class, $content, $status);
    }
}

/**
 * The Auth object
 * 
 * @return App\Core\Auth
 */
if (!function_exists('auth')) {
    function auth(): App\Core\Auth
    {
        return app(App\Core\Auth::class);
    }
}

/**
 * The input object
 * 
 * @return App\Core\Input
 */
if (!function_exists('input')) {
    function input(): App\Core\Input
    {
        return app(App\Core\Input::class);
    }
}

/**
 * The request object
 * 
 * @param string $key
 * @param mixed $default
 * 
 * @return App\Core\Request|mixed
 */
if (!function_exists('request')) {
    function request($key = null, $default = null)
    {
        $request = app(App\Core\Request::class);
        if ($key) {
            return $request->find($key, $default);
        }
        return $request;
    }
}

/**
 * Get the flash message
 * 
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
if (!function_exists('flash')) {
    function flash($name, $default = null)
    {
        return App\Core\Session::flash($name, $default);
    }
}

/**
 * Check the string is json or not
 * @param string $string
 * @return boolean
 */
if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

/**
 * Check the starts with 
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }
}

/**
 * Check the ends with 
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
if (!function_exists('endsWith')) {
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}

/**
 * Check the string is contain
 * @param string $needle
 * @param string $haystack
 * @return boolean
 */
if (!function_exists('strContains')) {
    function strContains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }
}

/**
 * Generate the URL
 * @param  string $path
 * @param  array  $params
 * @return string
 */
if (!function_exists('url')) {
    function url($path, $params = [])
    {
        $parameters = http_build_query($params);
        if (!empty($parameters)) {
            $parameters = "?{$parameters}";
        }

        if (startsWith($path, 'http')) {
            return "{$path}{$parameters}";
        }

        $baseUrl = defined('APP_URL') ? APP_URL : input()->baseUrl();
        if (!startsWith($path, '/') && !startsWith($path, 'http')) {
            $path = "/{$path}";
        }

        return "{$baseUrl}{$path}{$parameters}";
    }
}

/**
 * Generate the ASSER URL
 * @param  string $path
 * @return string
 */
if (!function_exists('assets')) {
    function assets($path)
    {
        $baseUrl = defined('APP_URL') ? APP_URL : input()->baseUrl();
        if (!startsWith($path, '/') && !startsWith($path, 'http')) {
            $path = "/{$path}";
        }

        return "{$baseUrl}/assets{$path}";
    }
}

/**
 * Redirect to
 * @param string $path
 * @return void
 */
if (!function_exists('redirectTo')) {
    function redirectTo($path)
    {
        header("Location: {$path}");
        exit();
    }
}

/**
 * Dump and die 
 * @return void
 */
if (!function_exists('dump')) {
    function dump()
    {
        foreach (func_get_args() as $data) {
            echo '<div style="
            padding: 15px;
            width: 800px;
            overflow: scroll;
            background: antiquewhite;
            margin: 20px auto;">';
            highlight_string("<?php\n\n" . var_export($data, true) . "\n\n?>\n");
            echo '</div>';
        }
    }
}

/**
 * Dump and die 
 * @return void
 */
if (!function_exists('dd')) {
    function dd()
    {
        dump(...func_get_args());
        die;
    }
}

/**
 * Check the request method
 * @param string $is
 * @return boolean
 */
if (!function_exists('request_method')) {
    function request_method($is)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        return strtolower($method) === strtolower($is);
    }
}
