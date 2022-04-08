<?php

require_once "base.php";
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

    public static function array_from_statement(PDOStatement $statement): array
    {
        $list = array();

        // iterate over rows
        while ($result = $statement->fetch(PDO::FETCH_NUM)) {
            // metadata of the query result
            $indexes = DbManager::get_indexes_array($statement);

            // build the temp user from the row
            $user = new User(
                DbManager::get_column($indexes, $result, User::$table, "id"),
                DbManager::get_column($indexes, $result, User::$table, "email"),
                DbManager::get_column($indexes, $result, User::$table, "password"),
                DbManager::get_column($indexes, $result, User::$table, "role_id"),
                array()
            );

            // build the temp region from the row
            $region = new Region(
                DbManager::get_column($indexes, $result, Region::$table, "id"),
                DbManager::get_column($indexes, $result, Region::$table, "name")
            );

            // check if the user is in the list
            if (array_key_exists($user->id, $list)){
                // get the user from the list
                $user = $list[$user->id];
            } else {
                // add the user in the list
                $list[$user->id] = $user;
            }

            // if the region is in the row add it to the user region list
            if ($region->id) {
                array_push($user->regions, $region);
            }
        }

        return $list;
    }

    public static function from_statement(PDOStatement $statement): ?User
    {
        $users = User::array_from_statement($statement);

        // if there are more than 0 users return the first
        if (count($users)) {
            return $users[array_key_first($users)];
        }
        return null;
    }
}

?>