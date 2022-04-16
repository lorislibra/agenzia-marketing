<?php

require_once("dto.php");

class SignInDto extends BaseDto
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
    static function from_array(array $array, array &$errors=array()): self
    {
        if (!self::validate_array($array, ["email", "password"], $errors)) {
            throw new ValidateDtoError();
        }

        $dto = new self($array["email"], $array["password"]);
        if (!$dto->validate($errors)) {
            throw new ValidateDtoError();
        }

        return $dto;
    }

    // check if the dto is valid
    function validate(array &$errors): bool
    {
        $is_valid = true;

        if (strlen($this->password) < 8) {
            array_push($errors, "password is short");
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