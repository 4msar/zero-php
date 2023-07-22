<?php

namespace App\Core;

/**
 * Base Class
 */
class Base extends \stdClass
{
    /**
     * Store data in the instance
     * example: $instance->foo = 'bar'
     * 
     * @param string $name  
     * @param string $value 
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Get the value from instance
     * @param  string $name 
     * @return mixed
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            if (method_exists($this, 'get')) {
                return $this->get($name);
            }
            return null;
        }
        return $this->$name;
    }

    /**
     * Call method of the instance
     * @param  string $method 
     * @param  mixed $args   
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            return null;
        }
        return $this->$method(...$args);
    }

    /**
     * Call method statically
     * @param  string $method 
     * @param  mixed $args   
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = new self();
        if (!method_exists($instance, $method)) {
            return $instance;
        }
        return $instance->$method(...$args);
    }
}
