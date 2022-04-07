<?php

class SignInDto
{
    public int $id;
    public string $email;
    public string $password;
    public int $role;
    public array $states;

    function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function from_array(array $array): self
    {
        return new self($array["email"], $array["password"]);
    }

    public function validate(array $errors): bool
    {
        return false;
    } 
}

?>