<?php

require_once("dto.php");

class ShowOrderDto extends BaseDto
{
    public int $id;

    function __construct(int $id)
    {
        $this->id = $id;
    }

    static function from_array(array $array): self
    {
        $errors = array();

        if (!self::validate_array($array, ["id"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["id"]);;
    }

    // check if input are valids
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "id is not a number");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>