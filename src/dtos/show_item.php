<?php

require_once("dto.php");

class ShowItemDto extends BaseDto
{
    public int $id;

    function __construct(string $id)
    {
        $this->id = $id;
    }

    static function from_array(array $array): self
    {
        $errors = array();

        if (!self::validate_array($array, ["id"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        $dto = new self($array["id"]);
        if (!$dto->validate($errors)) {
            throw new ValidateDtoError($errors);
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

        return $is_valid;
    }
}

?>