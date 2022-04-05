<?php

class SignInDto {
    public $id;
    public $email;
    public $password;
    public $role;
    public $states;

    function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }
}
?>