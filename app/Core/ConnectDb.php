<?php

namespace App\Core;

/**
 * Connect DB Class
 */
class ConnectDb
{
    // Hold the class instance.
    private static $instance = null;
    private $connection;

    // The db connection is established in the private constructor.
    private function __construct()
    {
        $this->connection = new \mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->connection->connect_errno) {
            throw new \Exception("Error Connecting Database", 500);
        }
    }

    /**
     * Get the DB instance
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    /**
     * Get the connection of the DB
     * @return \mysqli
     */
    public function getConnection(): \mysqli
    {
        return $this->connection;
    }

    /**
     * Close the connection
     */
    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
