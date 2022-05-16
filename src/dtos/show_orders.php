<?php

require_once("dto.php");

class ShowOrdersDto extends BaseDto
{
    public int $page;
    public int $per_page;

    function __construct(int $page=1, int $per_page=20)
    {
        $this->page = $page;
        $this->per_page = $per_page;
    }

    static function from_array(array $array): self
    {
        $errors = array();

        if (!self::validate_array($array, [], $errors, ["page", "per_page"])) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["page"], $array["per_page"]);;
    }

    // check if input are valids
    static function validate(array &$errors, array &$array): bool
    {
        $is_valid = true;

        if (is_null($array["page"]) || !filter_var($array["page"], FILTER_VALIDATE_INT) || $array["page"] < 1) {
            array_push($errors, "page is not a number");
            $array["page"] = 1;
        }

        if (is_null($array["per_page"]) || !filter_var($array["per_page"], FILTER_VALIDATE_INT) || $array["per_page"] < 1) {
            array_push($errors, "per_page is not a number");
            $array["per_page"] = 20;
        }

        $array["per_page"] = min($array["per_page"], 50);
        
        return $is_valid;
    }
}

?>