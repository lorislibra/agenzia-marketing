<?php

use PhpOption\None;

require_once "src/repositories/manager.php";
require_once "src/repositories/metadata.php";
require_once "region.php";

// User class that reflec user table in the database
class User
{
    public static $table = "user";

    public int $id;
    public string $email;
    public string $password;
    public int $role_id;
    public array $regions;

    function __construct(int $id, string $email, string $password, int $role_id, array $regions)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
        $this->regions = $regions;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "email", "password", "role_id"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $email = DbManager::get_column($metadata, $row, self::$table, "email");
        $password = DbManager::get_column($metadata, $row, self::$table, "password");
        $role_id = DbManager::get_column($metadata, $row, self::$table, "role_id");

        if ($id && $email && $password && $role_id) {
            return new self($id, $email, $password, $role_id, array());
        }

        return new MissingColumnError();
    }
}

?>