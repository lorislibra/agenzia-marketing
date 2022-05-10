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
    static function validate_array(array &$array,  array $requireds, array &$errors, array $optionals=[]): bool
    {
        $is_valid = true;

        foreach ($requireds as $required) {
            if (!isset($array[$required]) || is_null($array[$required]) || $array[$required] === "") {
                array_push($errors, "$required is invalid");
                $is_valid = false;
            }
        }

        foreach ($optionals as $optional) {
            if (!isset($array[$optional]) || is_null($array[$optional]) || $array[$optional] === "") {
                $array[$optional] = null;
            }
        }

        return $is_valid;
    }
}

?>