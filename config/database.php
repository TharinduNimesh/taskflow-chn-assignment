<?php

class Database {
    private static $instance = null;
    private $connection;

    private $host = 'db';
    private $database = 'taskflow_db';
    private $username = 'root';
    private $password = 'rootpassword';

    private function __construct() {
        $this->connection = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if (mysqli_connect_errno()) {
            $error = "Database Connection Error: " . mysqli_connect_error();
            error_log($error);
            throw new Exception($error);
        }

        mysqli_set_charset($this->connection, 'utf8');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    public function __wakeup() {}
}