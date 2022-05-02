<?php

require_once("dto.php");

class CreateOrderDto extends BaseDto
{
    public int $sell_point_id;

    function __construct(int $sell_point_id)
    {
        $this->sell_point_id = $sell_point_id;
    }

    // parse from an array
    static function from_array(array $array): self
    {
        $errors = array();
        
        if (!self::validate_array($array, ["sell_point_id"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["sell_point_id"]);
    }

    // check if the dto is valid
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["sell_point_id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "sell_point_id is not a number");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>