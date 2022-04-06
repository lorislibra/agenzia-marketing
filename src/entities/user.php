<?php

// User class that reflec user table in the database
class User {
    public int $id;
    public string $email;
    public string $password;
    public int $role_id;
    public array $states;

    function __construct(int $id, string $email, string $password, int $role_id, array $states) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
        $this->states = array($states);
    }

    public static function from_array(array $resp): User {
        // TODO: check of the array
        // TODO: implement join of states
        return new User($resp["id"], $resp["email"], $resp["password"], $resp["role_id"], array());
    }
}

?>