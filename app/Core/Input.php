<?php

namespace App\Core;

/**
 * Input / Request handler
 */
class Input extends Base
{
    /**
     * Get the protocol & host
     *
     * @return  string
     */
    static public function baseUrl()
    {
        $protocol = self::protocol();
        $host = self::server('HTTP_HOST');
        return "{$protocol}://{$host}";
    }

    /**
     * Full Url
     *
     * @return  string
     */
    static public function currentUrl()
    {
        $protocol = self::protocol();
        $host = self::server('HTTP_HOST');
        $uri = self::server('REQUEST_URI');

        return "{$protocol}://{$host}{$uri}";
    }

    /**
     * Get The Server Host name
     *
     * @return  string
     */
    static public function host()
    {
        return self::server('HTTP_HOST');
    }

    /**
     * Path
     *
     * @return  string
     */
    static public function path()
    {
        $url = self::currentUrl();
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Protocol (http or https)
     *
     * @return  string
     */
    static public function protocol()
    {
        $secure = (self::server('HTTP_HOST') && self::server('HTTPS') && strtolower(self::server('HTTPS')) !== 'off') ? true : false;

        return ($secure) ? 'https' : 'http';
    }


    /**
     * Is AJAX request
     *
     * @return  bool
     */
    static public function isAjax()
    {
        return (self::server('HTTP_X_REQUESTED_WITH') && strtolower(self::server('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') ? true : false;
    }

    /**
     * Is Json request
     *
     * @return  bool
     */
    static public function isJson()
    {
        return self::header('Content-type') === 'application/json' ||
            self::header('Accept') === 'application/json';
    }


    /**
     * Request method (usually GET or POST)
     *
     * @param   bool    $upper
     * @return  string
     */
    static public function method($upper = true)
    {
        $method = self::server('REQUEST_METHOD');

        return ($upper) ? strtoupper($method) : strtolower($method);
    }

    /**
     * Request method (usually GET or POST)
     *
     * @param   bool    $upper
     * @return  string
     */
    static public function isMethod($name)
    {
        $method = self::method(true);

        return $method === strtoupper($name);
    }


    /**
     * Referrer
     *
     * @param   string  $default
     * @return  string
     */
    static public function referrer($default = null)
    {
        // If HTTP_REFERER is not found in $_SERVER, $default will be returned
        $referrer = self::server('HTTP_REFERER', $default);

        // If the referrer is null, and the default value is not null, set the referrer to $default
        if (is_null($referrer) && !is_null($default)) {
            $referrer = $default;
        }

        return $referrer;
    }


    /**
     * Find $_SERVER value
     *
     * @param   string  $index
     * @param   string  $default
     * @return  mixed
     */
    static public function server($index = '', $default = null)
    {
        return self::_findFromArray($_SERVER, $index, $default, false);
    }

    /**
     * Get All Request Value
     *
     * @return  mixed
     */
    static public function all()
    {
        $content = file_get_contents('php://input');
        if (isJson($content)) {
            return json_decode($content, true);
        }
        return array_merge($_GET, $_POST);
    }

    /**
     * Find a value from all input data
     *
     * @return  mixed
     */
    static public function find($item = null, $default = null)
    {
        return self::_findFromArray(self::all(), $item, $default, true);
    }

    /**
     * Find $_GET value
     *
     * @param   string  $item
     * @param   string  $default
     * @param   bool    $xss_clean
     * @return  mixed
     */
    static public function get($item = null, $default = null, $xss_clean = true)
    {
        return self::_findFromArray($_GET, $item, $default, $xss_clean);
    }


    /**
     * Find $_POST value
     *
     * @param   string  $item
     * @param   string  $default
     * @param   bool    $xss_clean
     * @return  mixed
     */
    static public function post($item = null, $default = null, $xss_clean = true)
    {
        if (self::isJson()) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }
        return self::_findFromArray($_POST, $item, $default, $xss_clean);
    }


    /**
     * Find $_GET/$_POST value (similar to $_REQUEST except it does not include $_COOKIE)
     *
     * @param   string  $item
     * @param   string  $default
     * @param   bool    $xss_clean
     * @return  mixed
     */
    static public function request($item = null, $default = null, $xss_clean = true)
    {
        $request = array_merge($_GET, $_POST);

        return self::_findFromArray($request, $item, $default, $xss_clean);
    }


    /**
     * Find $_FILE value. If a file field was submitted without a file selected, this may still return a value. It is best to use this method along with Input::hasFile()
     *
     * @param   string  $item
     * @param   string  $default
     * @return  mixed
     */
    static public function file($item = null, $default = null)
    {
        return self::_findFromArray($_FILES, $item, $default, false);
    }

    /**
     * Find headers from all the headers
     *
     * @param   string  $item
     * @param   string  $default
     * @return  mixed
     */
    static public function header($item = null, $default = null)
    {
        return self::_findFromArray(getallheaders(), $item, $default, false);
    }


    /**
     * Is an item available in the $_GET request? Checks for existence of key, not empty value
     *
     * @param   string  $item
     * @return  bool
     */
    static public function inGet($item = null)
    {
        return (is_null(self::get($item, null, false))) ? false : true;
    }


    /**
     * Is an item available in the $_POST request? Checks for existence of key, not empty value
     *
     * @param   string  $item
     * @return  bool
     */
    static public function inPost($item = null)
    {
        return (is_null(self::post($item, null, false))) ? false : true;
    }


    /**
     * Is an item available in $_GET/$_POST request? Checks for existence of key, not empty value
     *
     * @param   string  $item
     * @return  bool
     */
    static public function inRequest($item = null)
    {
        return (is_null(self::request($item, null, false))) ? false : true;
    }


    /**
     * Is an item available in $_FILE request? Checks for existence of key, not empty array value
     *
     * @param   string  $item
     * @return  bool
     */
    static public function inFile($item = null)
    {
        return (is_null(self::file($item, null))) ? false : true;
    }


    /**
     * Check if a file field selected a file to be uploaded
     *
     * @param   string  $item
     * @return  bool
     */
    static public function hasFile($item = null)
    {
        $file = self::file($item, null);

        return (!is_null($file) && strlen($file['tmp_name']) > 0) ? true : false;
    }


    /**
     * Clean data for XSS
     *
     * @param   string|array    $str
     * @return  mixed
     */
    static public function xssClean($str = '')
    {
        // No data? We're done here
        if (is_string($str) && trim($str) === '') {
            return $str;
        }

        // Recursive sanitize if this is an array
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = self::xssClean($value);
            }

            return $str;
        }

        $str = str_replace(array(
            '&amp;',
            '&lt;',
            '&gt;'
        ), array(
            '&amp;amp;',
            '&amp;lt;',
            '&amp;gt;'
        ), $str);

        // Fix &entitiy\n;
        $str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u', '$1;', $str);
        $str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', '$1$2;', $str);
        $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');

        // remove any attribute starting with "on" or xmlns
        $str = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>#iUu', '$1>', $str);

        // remove javascript: and vbscript: protocol
        $str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2nojavascript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2novbscript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*-moz-binding[\x00-\x20]*:#Uu', '$1=$2nomozbinding...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*data[\x00-\x20]*:#Uu', '$1=$2nodata...', $str);

        // Remove any style attributes, IE allows too much stupid things in them, eg.
        // <span style="width: expression( alert( 'Ping!' ));"></span>
        // and in general you really don't want style declarations in your UGC
        $str = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])style[^>]*>#iUu', '$1>', $str);

        // Remove namespaced elements (we do not need them...)
        $str = preg_replace('#</*\w+:\w[^>]*>#i', '', $str);

        // Remove really unwanted tags
        do {
            $oldstring = $str;
            $str = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', '', $str);
        } while ($oldstring != $str);

        return $str;
    }


    /**
     * Find in array
     *
     * @param   array   $array
     * @param   string  $item
     * @param   mixed   $default
     * @param   bool    $xss_clean
     * @return  mixed
     */
    static private function _findFromArray($array = array(), $item = '', $default = null, $xss_clean = true)
    {
        // If the array is empty, we are done. Return default value
        if (empty($array)) {
            return $default;
        }

        // Check if an item has been provided. If not, return the entire sanitized array
        if (!$item) {
            $arr = array();
            // loop through the full array
            foreach (array_keys($array) as $key) {
                $arr[$key] = self::_fetchFromArray($array, $key, $default, $xss_clean);
            }

            return $arr;
        }

        // Return sanitized item
        return self::_fetchFromArray($array, $item, $default, $xss_clean);
    }


    /**
     * Fetch from array
     *
     * @param   array   $array
     * @param   string  $item
     * @param   string  $default
     * @param   bool    $xss_clean
     * @return  mixed
     */
    static private function _fetchFromArray($array, $item = '', $default = null, $xss_clean = true)
    {
        // Not found. Return default
        if (!isset($array[$item])) {
            return $default;
        }

        // Found it! Now clean it
        if ($xss_clean) {
            return self::xssClean($array[$item]);
        }

        // Found it! Return uncleaned
        return $array[$item];
    }
}
