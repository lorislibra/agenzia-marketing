<?php

require_once "base.php";
require_once "region.php";

// User class that reflec user table in the database
class User {
    public static $table = "user";

    public int $id;
    public string $email;
    public string $password;
    public int $role_id;
    public array $regions;

    function __construct(int $id, string $email, string $password, int $role_id, array $regions){
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
        $this->regions = $regions;
    }

    public static function array_from_statement(PDOStatement $statement): array {
        $list = array();

        while ($result = $statement->fetch(PDO::FETCH_NUM)) {
            $user = new User(
                get_column($statement, $result, User::$table, "id"),
                get_column($statement, $result, User::$table, "email"),
                get_column($statement, $result, User::$table, "password"),
                get_column($statement, $result, User::$table, "role_id"),
                array()
            );

            $region = new Region(
                get_column($statement, $result, Region::$table, "id"),
                get_column($statement, $result, Region::$table, "name")
            );

            if ($region->id){
                if (array_key_exists($user->id, $list)){
                    $user = $list[$user->id];
                }else{
                    $list[$user->id] = $user;
                }
                array_push($user->regions, $region);
            }
        }

        return $list;
    }

    public static function from_statement(PDOStatement $statement): ?User {
        $users = User::array_from_statement($statement);

        if (count($users)){
            return $users[array_key_first($users)];
        }
        return null;
    }
}

?>