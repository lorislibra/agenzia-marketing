<?php

require_once("dto.php");

class AddToCartDto extends BaseDto
{
    public int $item_id;
    public int $quantity;

    function __construct(string $item_id, string $quantity)
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

        $dto = new self($array["item_id"], $array["quantity"]);
        if (!$dto->validate($errors)) {
            throw new ValidateDtoError($errors);
        }

        return $dto;
    }

    // check if the dto is valid
    function validate(array &$errors): bool
    {
        $is_valid = true;

        if (!filter_var($this->item_id, FILTER_VALIDATE_INT)) {
            array_push($errors, "id is not a number");
            $is_valid = false;
        }

        if (!filter_var($this->quantity, FILTER_VALIDATE_INT)) {
            array_push($errors, "quantity is not a number");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>