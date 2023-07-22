<?php

namespace App\Core;

/**
 * Collection
 */
class Collection extends Base
{
    /**
     * Items to be load
     * @var array
     */
    protected $items;

    /**
     * Collection type
     * 
     * ex: collection | object
     * @var string
     */
    protected $type = "object";

    /**
     * Load the data
     * @param array $items 
     */
    function __construct($items = [], $type = 'object')
    {
        $this->items = $items;
        $this->type = $type;
        $this->manipulateData();
    }

    /**
     * Check is there was data 
     * @return boolean 
     */
    public function has()
    {
        if (is_null($this->items)) {
            return false;
        }
        return is_array($this->items) && count($this->items) > 0;
    }

    /**
     * Assign all the value to the object
     * @return void 
     */
    private function manipulateData()
    {
        if (!is_array($this->items)) {
            return;
        }

        foreach ($this->items as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get the all items
     * @return array 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the item value by key
     * @param  string $key 
     * @return string      
     */
    public function get($key)
    {
        return $this->{$key} ?? null;
    }

    /**
     * Count the items
     * @return number      
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get the items
     * @return array 
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Get the JOSN value of the Items
     * @return string 
     */
    public function toJson()
    {
        return json_encode($this->items, JSON_PRETTY_PRINT);
    }


    /**
     * Get the items
     * @return array 
     */
    public function __toArray()
    {
        return $this->toArray();
    }

    /**
     * Load the items as string
     * @return string 
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
