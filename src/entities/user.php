<?php

require_once("src/repositories/manager.php");

enum Role: int
{
    case Developer = 1;
    case Warehouse = 2;
    case GroupAdmin = 3;
    case RegionAdmin = 4;

    function important_than(self $other): bool
    { 
        return $this <= $other;
    }
}

// User class that reflec user table in the database
class User
{
    public static $table = "user";

    public int $id;
    public string $email;
    public string $password;
    public Role $role;
    public array $regions;

    function __construct(int $id, string $email, string $password, Role $role, array $regions)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
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

        $role = Role::from($role_id);
        return new self($id, $email, $password, $role, array());
    }
}

?>