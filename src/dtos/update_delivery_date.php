<?php

require_once("dto.php");

class UpdateDeliveryDateDto extends BaseDto
{
    public int $reservation_id;
    public DateTime $delivery_date;

    function __construct(int $reservation_id, DateTime $delivery_date)
    {
        $this->reservation_id = $reservation_id;
        $this->delivery_date = $delivery_date;
    }

    // parse from an array
    static function from_array(array $array): self
    {
        $errors = array();
        
        if (!self::validate_array($array, ["reservation_id", "delivery_date"], $errors)) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["reservation_id"], new DateTime($array["delivery_date"]));
    }

    // check if the dto is valid
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (!filter_var($array["reservation_id"], FILTER_VALIDATE_INT)) {
            array_push($errors, "reservation_id is not a number");
            $is_valid = false;
        }

        if (empty($array["delivery_date"])) {
            array_push($errors, "delivery_date is not valid");
            $is_valid = false;
        }

        try {
            $date = new DateTime($array["delivery_date"]);
        } catch (Exception $e) {
            array_push($errors, "delivery_date is not valid");
            $is_valid = false;
        }

        return $is_valid;
    }
}

?>