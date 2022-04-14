<?php

require_once("src/config.php");
require_once("metadata.php");

// https://phpdelusions.net/pdo

class DbManager
{

    private PDO $connection;

    // build a pdo connection from arguments
    public static function build_connection(string $host, string $port, string $user, string $passw, string $db_name): PDO
    {
        $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];
        return new PDO($dsn, $user, $passw, $options);
    }

    // build a pdo connection from env vars
    public static function build_connection_from_env(): PDO
    {
        $host = getenv("DB_HOST");
        $port = getenv("DB_PORT");
        $user = getenv("DB_USER");
        $passw = getenv("DB_PASSW");
        $db_name = getenv("DB_NAME");

        return DbManager::build_connection($host, $port, $user, $passw, $db_name);
    }

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function get_connection(): PDO
    {
        return $this->connection;
    }

    // get specific column from a row
    public static function get_column(QueryMetadata $metadata, array $result, string $table, string $column)
    {
        $index = $metadata->get($table, $column);
        if ($index !== null) {
            if (($column_value = $result[$index]) !== null) {
                return $column_value;
            }
        }

        throw new MissingColumnError();
    }

    public function get_first_element(array $list): ?object
    {
        if (count($list)) {
            return $list[array_key_first($list)];
        }
        return null;
    }

}

?>