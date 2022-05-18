<?php

require_once("dto.php");

class UpdateCommentDto extends BaseDto
{
    public int $reservation_id;
    public string $comment;

    function __construct(int $reservation_id, string $comment)
    {
        $this->reservation_id = $reservation_id;
        $this->comment = $comment;
    }

    // parse from an array
    static function from_array(array $array): self
    {
        $errors = array();
        
        if (!self::validate_array($array, ["reservation_id", "comment"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["reservation_id"], $array["comment"]);
    }

    // check if the dto is valid
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["reservation_id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "reservation_id is not a number");
            $is_valid = false;
        }

        if (empty($array["comment"])) {
            array_push($errors, "comment is not valid");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>