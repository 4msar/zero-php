<?php

namespace App\Core;

use App\Core\Traits\Queries;

/**
 * Base Model
 */
abstract class Model extends Base
{
    use Queries;

    /**
     * The table name
     * @var string
     */
    protected $table;

    /**
     * Bind sub-query result
     * @var array
     */
    protected $with = [];

    /**
     * Get the mysql connection instance
     * 
     * @return mysqli
     */
    public function getConnection(): \mysqli
    {
        return ConnectDb::getInstance()->getConnection();
    }

    /**
     * Get the DB table name
     * 
     * @param  array $data
     * @return string
     */
    public function getTableName()
    {
        if ($this->table) {
            return $this->table;
        }
        return strtolower(self::class);
    }

    /**
     * Get the result without merging the $with data
     * 
     * @return object
     */
    public function with($with = [])
    {
        $this->with = $with;
        return $this;
    }

    /**
     * Run a simple DB query
     * 
     * @param  string $query
     * @return object|boolean
     */
    public function runSimpleQuery($query)
    {
        return $this->getConnection()->query($query);
    }

    /**
     * Get the last insert id
     * 
     * @return number
     */
    public function getLastInsertId()
    {
        return $this->getConnection()->insert_id;
    }

    /**
     * Run multiple DB query
     * 
     * @param  string $query
     * @return object|boolean
     */
    public function runMultipleQuery($query)
    {
        return $this->getConnection()->multi_query($query);
    }

    /**
     * Run DB Query and get the result
     * 
     * @param  string $query
     * @param  boolean $single
     * @return object|array|null
     */
    function runQuery($query, $single = false)
    {
        $result = $this->runSimpleQuery($query);

        if (!$result) return null;

        if ($single) {
            return $this->prepareSingleResult($result);
        }
        return $this->prepareResult($result, $single);
    }

    /**
     * Prepare the single result
     * 
     * @param  object $result
     * @return object|boolean
     */
    protected function prepareSingleResult($result)
    {
        if (!$result) return null;
        $item =  $result->fetch_assoc();
        return $this->mergeWiths($this->parseItem($item ?? []));
    }

    /**
     * A middleware for the data
     * @param mixed $data
     * @return array|object|\App\Core\Collection
     */
    public function parseItem($item)
    {
        return $item;
    }

    /**
     * A middleware for the all data 
     * @param array $data
     * @return array|object|\App\Core\Collection
     */
    public function parseItems($data)
    {
        return $data;
    }

    /**
     * Merge additional data
     * @param  mixed $item 
     * @return mixed
     */
    public function mergeWiths($item)
    {
        if (!is_array($this->with) || count($this->with) <= 0) {
            return $item;
        }

        foreach ($this->with as $key => $value) {
            if (method_exists($this, $key)) {
                if (is_array($item)) {
                    $item[$key] = $this->$key($item[$value]);
                } elseif ($item instanceof Collection) {
                    $item->{$key} = $this->$key($item->{$value});
                }
            }
        }
        return $item;
    }

    /**
     * Prepare the multiple result
     * 
     * @param  object $result
     * @return object|array
     */
    protected function prepareResult($result)
    {
        if (!$result) return null;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $this->mergeWiths($this->parseItem($row));
        }
        return $this->parseItems($data);
    }
}
