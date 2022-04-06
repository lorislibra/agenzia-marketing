<?php

require_once "src/config.php";

// https://phpdelusions.net/pdo

class DbManager {

    private PDO $connection;

    public static function build_connection(string $host, string $port, string $user, string $passw, string $db_name): PDO {
        $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        return new PDO($dsn, $user, $passw, $options);
    }

    public static function build_connection_from_env(): PDO {
        $host = $_ENV["DB_HOST"];
        $port = $_ENV["DB_PORT"];
        $user = $_ENV["DB_USER"];
        $passw = $_ENV["DB_PASSW"];
        $db_name = $_ENV["DB_NAME"];

        return DbManager::build_connection($host, $port, $user, $passw, $db_name);
    }

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function get_connection(): PDO {
        return $this->connection;
    }
}

?>