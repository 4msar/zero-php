<?php

namespace App\Core;

/**
 * Session Handler
 */
class Session extends Base
{
    const SESSION_LIFETIME = 3600 * 24 * 7; // 7 days

    const FLASH_MESSAGE_KEY = '__FLASH__';

    /**
     * Start the session when initiate the class
     */
    function __construct()
    {
        self::start();
        if (!isset($_SESSION[self::FLASH_MESSAGE_KEY])) {
            $_SESSION[self::FLASH_MESSAGE_KEY] = [];
        }
    }

    /**
     * Start the session
     * @return void 
     */
    static public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => self::SESSION_LIFETIME
            ]);
        }
    }

    /**
     * Check the session is exist
     *
     * @param string $name
     * @return boolean
     */
    static public function exist($name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Check the session is exist and has value
     *
     * @param string $name
     * @return boolean
     */
    static public function has($name)
    {
        return isset($_SESSION[$name]) && !empty($_SESSION[$name]);
    }

    /**
     * Get the session value
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    static public function get($name, $default = null)
    {
        return $_SESSION[$name] ?? $default;
    }

    /**
     * Set the session value
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    static public function set($name, $value)
    {
        $_SESSION[$name] = $value;
        return $value;
    }

    /**
     * Set the session value
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    static public function put($name, $value)
    {
        $_SESSION[$name] = $value;
        return $value;
    }

    /**
     * Delete the specific session value
     *
     * @param string $name
     * @return boolean
     */
    static public function delete($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
        return true;
    }

    /**
     * Add new flash item
     *
     * @param string $name
     * @param string $message
     * @return mixed
     */
    static public function addFlash($name, $message = "")
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                self::addFlash($key, $value);
            }
            return true;
        }
        $_SESSION[self::FLASH_MESSAGE_KEY][$name] = $message;
        return $message;
    }

    /**
     * Show the flash message
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    static public function flash($name, $default = null)
    {
        $message = $default;
        if (isset($_SESSION[self::FLASH_MESSAGE_KEY][$name])) {
            $message = $_SESSION[self::FLASH_MESSAGE_KEY][$name];
        }
        unset($_SESSION[self::FLASH_MESSAGE_KEY][$name]);
        return $message;
    }

    /**
     * Delete the all session value
     *
     * @return void
     */
    static public function destroy()
    {
        session_destroy();
    }
}
