<?php

class ShowItemDto
{
    public int $id;

    function __construct(string $id)
    {
        $this->id = $id;
    }

    // parse from an array
    public static function from_array(array $array): self
    {
        return new self($array["id"]);
    }

    // check if the dto is valid
    function validate(array &$errors): bool
    {
        $is_valid = true;

        if (!filter_var($this->id, FILTER_VALIDATE_INT)) {
            array_push($errors, "id is not a number");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>