<?php

require_once("dto.php");

class ShowItemsDto extends BaseDto
{
    public int $page;
    public int $per_page;
    public string $query;

    function __construct(int $page=1, int $per_page=20, string $query="")
    {
        $this->page = $page;
        $this->per_page = $per_page;
        $this->query = $query;
    }

    static function from_array(array $array): self
    {
        $errors = array();

        if (!self::validate_array($array, [], $errors, ["page", "per_page", "query"])) {
            throw new ValidateDtoError($errors);
        }

        if (!self::validate($errors, $array)) {
            throw new ValidateDtoError($errors);
        }

        return new self($array["page"], $array["per_page"], $array["query"]);;
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
        
        if (is_null($array["query"])) {
            array_push($errors, "query is not a string");
            $array["query"] = "";
        }

        return $is_valid;
    }
}

?>