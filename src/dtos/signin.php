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

    // parse from an array
    public static function from_array(array $array): self
    {
        return new self($array["email"], $array["password"]);
    }

    // check if the dto is valid
    function validate(array &$errors): bool
    {
        $is_valid = true;

        if (strlen($this->password) < 8) {
            array_push($errors, "password is too short");
            $is_valid = false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "email is not valid");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>