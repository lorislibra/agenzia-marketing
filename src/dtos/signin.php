<?php

class SignInDto {
    public int $id;
    public string $email;
    public string $password;
    public int $role;
    public array $states;

    function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }
}
?>