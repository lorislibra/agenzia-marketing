<?php

class User {
    public $id;
    public $email;
    public $password;
    public $role;
    public $states;

    function __construct(int $id, string $email, string $password, string $role, array $states) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->states = array($states);
    }
}

?>