<?php

// https://phpdelusions.net/pdo

class DbManager {

    private PDO $connection;

    public static function build_connection(string $host, string $user, string $passw, string $db_name): PDO {
        $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $user, $passw, $options);
    }

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function get_connection(): PDO {
        return $this->connection;
    }
}

?>