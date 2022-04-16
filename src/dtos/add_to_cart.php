<?php

require_once("dto.php");

class AddToCartDto extends BaseDto
{
    public int $id;
    public int $quantity;

    function __construct(string $id, string $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }

    // parse from an array
    static function from_array(array $array, array &$errors=array()): self
    {
        if (!self::validate_array($array, ["id", "quantity"], $errors)) {
            throw new ValidateDtoError();
        }

        $dto = new self($array["id"], $array["quantity"]);
        if (!$dto->validate($errors)) {
            throw new ValidateDtoError();
        }

        return $dto;
    }

    // check if the dto is valid
    function validate(array &$errors): bool
    {
        $is_valid = true;

        if (!filter_var($this->id, FILTER_VALIDATE_INT)) {
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