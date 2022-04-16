<?php

class AddToCartDto
{
    public int $id;
    public int $quantity;

    function __construct(string $id, string $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }

    // parse from an array
    public static function from_array(array $array): self
    {
        return new self($array["id"], $array["quantity"]);
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