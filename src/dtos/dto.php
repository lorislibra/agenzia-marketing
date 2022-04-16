<?php

class ValidateDtoError extends Exception
{
    public readonly array $errors;

    function __construct(array $errors=array())
    {
        parent::__construct();
        $this->errors = $errors;
    }
}

class BaseDto
{
    static function validate_array(array $array,  array $arguments, array &$errors): bool
    {
        $is_valid = true;

        foreach ($arguments as $argument) {
            if (!isset($array[$argument]) || is_null($array[$argument]) || $array[$argument] === "") {
                array_push($errors, "$argument is invalid");
                $is_valid = false;
            }
        }

        return $is_valid;
    }
}

?>