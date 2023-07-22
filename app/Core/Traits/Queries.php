<?php

namespace App\Core\Traits;

/**
 * All DB Queries Helpers Functions
 */
trait Queries
{
    /**
     * Pagination per page
     * @var integer
     */
    protected $per_page = 15;

    /**
     * Get the data order by query
     * 
     * @var string
     */
    protected $orderQuery = "";

    /**
     * Find a single item from DB
     * 
     * @param  mixed $id primary key of the table
     * @return array
     */
    public function find($id)
    {
        return $this->runQuery("SELECT * from {$this->table} where id = {$id}", true);
    }

    /**
     * Get the data from DB by latest order
     *
     * @return self
     */
    function latest($column = "id")
    {
        $this->orderBy($column, "DESC");
        return $this;
    }

    /**
     * Get the data from DB by oldest order
     *
     * @return self
     */
    function oldest($column = "id")
    {
        $this->orderBy($column, "ASC");
        return $this;
    }

    /**
     * Get the data from DB by order
     *
     * @return self
     */
    function orderBy($column = "id", $order = "DESC"): self
    {
        if ($order === "DESC" || $order === 'ASC') {
            $this->orderQuery = "ORDER BY {$column} {$order}";
        }
        return $this;
    }

    /**
     * Run query with where condition
     * 
     * @param  array $where
     * @return array
     */
    public function where($clues = [], $single = false, $condition = "AND")
    {
        $whereArray = array_map(function ($key, $item) {
            return "{$key} = '{$item}'";
        }, array_keys($clues), $clues);

        $whereArray = implode(" {$condition} ", $whereArray);

        return $this->runQuery("SELECT * FROM {$this->table} WHERE {$whereArray} $this->orderQuery", $single);
    }

    /**
     * Run query to count items
     * 
     * @param  array $where
     * @return int
     */
    public function count($clues = [], $condition = "AND")
    {
        $whereArray = array_map(function ($key, $item) {
            return "{$key} = '{$item}'";
        }, array_keys($clues), $clues);
        $whereQuery = implode(" {$condition} ", $whereArray);

        if (count($whereArray) > 0) {
            $whereQuery = "WHERE {$whereQuery}";
        } else {
            $whereQuery = "";
        }

        $result = $this->runSimpleQuery("SELECT COUNT(*) as total FROM {$this->table} {$whereQuery}", true);
        return $result->fetch_assoc()['total'] ?? 0;
    }

    /**
     * Get all the items from DB
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->runQuery("SELECT * from {$this->table} {$this->orderQuery}");
    }

    /**
     * Paginate all the DB items
     * 
     * @param int $per_page 
     * @param  boolean $cursorPagination
     * @return array
     */
    public function paginate($per_page = null, $cursorPagination = false)
    {
        if ($per_page) {
            $this->per_page = $per_page;
        }

        $page = request('page', 1);
        $startAt = $this->per_page * ($page - 1);
        $total = 0;
        $total_pages = 0;

        if ($startAt < 0) {
            $startAt = 0;
        }

        if ($cursorPagination) {
            $cursor = request('cursor', 0);
            $startAt = $cursor;
            $total = 0;
            $total_pages = 0;
        } else {
            $total = $this->count();
            $total_pages = (int) ceil($this->count() / $this->per_page);
        }

        $result = $this->runQuery("SELECT * from {$this->table} {$this->orderQuery} limit {$startAt}, {$this->per_page}");

        return [
            'total' => $total ?? count($result),
            'items' => $result->toArray(),
            'pages' => $total_pages,
            'current' => $page
        ];
    }

    /**
     * Add new item in DB
     * 
     * @param  array $data
     * @return boolean
     */
    public function insert($data = [])
    {
        $query = $this->prepareSingleInputQuery($data);
        return $this->runSimpleQuery($query);
    }

    /**
     * Add new item in DB and get the insert id
     * 
     * @param  array $data
     * @return number
     */
    public function insertAndGetId($data = [])
    {
        $query = $this->prepareSingleInputQuery($data);
        $this->runSimpleQuery($query);
        return $this->getLastInsertId();
    }

    /**
     * Add multiple item in DB
     * 
     * @param  array $data
     * @return boolean
     */
    public function insertMany($data = [])
    {
        $queries = [];
        foreach ($data as $single) {
            $queries[] = $this->prepareSingleInputQuery($data);
        }
        return $this->runMultipleQuery(implode(';', $queries));
    }

    /**
     * Update a item in DB
     * 
     * @param  number $id
     * @param  array $data
     * @return boolean
     */
    public function update($id, $data = [])
    {
        $data = $this->prepareUpdateData($data);
        $sql = "UPDATE {$this->table} SET {$data} WHERE id={$id}";
        return $this->runSimpleQuery($sql);
    }

    /**
     * Delete an item in DB
     * 
     * @param  number $id
     * @return boolean
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id={$id}";
        return $this->runSimpleQuery($sql);
    }

    /**
     * Prepare the query for single data insert
     * 
     * @param  array $data
     * @return string
     */
    protected function prepareSingleInputQuery($data = [])
    {
        $columns = implode(',', array_keys($data));
        $initialValues = array_map(function ($item) {
            return $this->prepareValue($item);
        }, array_values($data));
        $values = implode(',', $initialValues);
        return "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
    }

    /**
     * Prepare the query for single data update
     * 
     * @param  array $data
     * @return string
     */
    protected function prepareUpdateData($data = [])
    {
        $queryText = "";
        foreach ($data as $key => $value) {
            $finalValue = $this->prepareValue($value);
            $queryText .= "{$key}={$finalValue},";
        }
        return rtrim($queryText, ',');
    }

    /**
     * Prepare the string 
     * 
     * @param  mixed $item
     * @return string
     */
    protected function prepareValue($item)
    {
        if (is_string($item)) {
            return "'{$item}'";
        } elseif (is_array($item)) {
            $json = json_encode($item);
            return "'{$json}'";
        } elseif (is_numeric($item)) {
            return $item;
        } elseif ($item == null) {
            return "NULL";
        } else {
            return "'{$item}'";
        }
    }
}
