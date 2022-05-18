<?php

require_once("dto.php");
require_once("src/entities/reservation.php");

class UpdateStatusDto extends BaseDto
{
    public int $reservation_id;
    public OrderStatus $status;

    function __construct(int $reservation_id, OrderStatus $status)
    {
        $this->reservation_id = $reservation_id;
        $this->status = $status;
    }

    // parse from an array
    static function from_array(array $array): self
    {
        $errors = array();
        
        if (!self::validate_array($array, ["reservation_id", "status"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["reservation_id"], OrderStatus::from($array["status"]));
    }

    // check if the dto is valid
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["reservation_id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "reservation_id is not a number");
            $is_valid = false;
        }

        if (!filter_var($array["status"], FILTER_VALIDATE_INT)) {
            array_push($errors, "status is not a number");
            $is_valid = false;
        }

        try { OrderStatus::from($array["status"]); }
        catch(Exception $e)
        {
            array_push($errors, "status doesn't exist");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>