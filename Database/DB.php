<?php

namespace Database;
require_once "config.php";

use mysqli;

class DB
{

    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->connection->connect_error) {
            die("連線失敗：" . $this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function createTableMessage()
    {
        $CREATE_KANBAN_TABLE = "CREATE TABLE IF NOT EXISTS messages (
                                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                name VARCHAR(50),
                                content VARCHAR(300)
                            )";
        return $this->connection->query($CREATE_KANBAN_TABLE);
    }
}