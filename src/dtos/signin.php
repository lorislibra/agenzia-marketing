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
    function validate(array $errors): bool
    {
        $is_valid = true;
        $len = strlen($this->password);
        if ($len > 7) {
            array_push($errors, $len);
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>