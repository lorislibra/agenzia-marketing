<?php

require_once "base.php";

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
            
            $id = get_column($statement, $result, User::$table, "id");
            $email = get_column($statement, $result, User::$table, "email");
            $password = get_column($statement, $result, User::$table, "password");
            $role_id = get_column($statement, $result, User::$table, "role_id");

            $region_id = get_column($statement, $result, "region", "id");
            $region_name = get_column($statement, $result, "region", "name");

            if ($region_id){
                if (array_key_exists($id, $list)){
                    $user = $list[$id];
                    array_push($user->regions, [$region_id, $region_name]);
                }else{
                    $user = new User($id, $email, $password, $role_id, array([$region_id, $region_name]));
                    array_push($list, $user);
                }
            }
        }
        return $list;
    }

    public static function from_statement(PDOStatement $statement): ?User {
        $users = User::array_from_statement($statement);
        if (count($users)){
            return $users[0];
        }
        return null;
    }
}

?>