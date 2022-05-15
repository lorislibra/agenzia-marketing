<?php

require_once("dto.php");

class UpdateCartDto extends BaseDto
{
    public int $item_id;
    public int $quantity;

    function __construct(int $item_id, string $quantity)
    {
        $this->item_id = $item_id;
        $this->quantity = $quantity;
    }

    // parse from an array
    static function from_array(array $array): self
    {
        $errors = array();
        
        if (!self::validate_array($array, ["item_id", "quantity"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["item_id"], $array["quantity"]);
    }

    // check if the dto is valid
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["item_id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "item_id is not a number");
            $is_valid = false;
        }

        if (!filter_var($array["quantity"], FILTER_VALIDATE_INT)) {
            array_push($errors, "quantity is not a number");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>