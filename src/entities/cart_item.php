<?php

require_once("src/repositories/manager.php");
require_once("user.php");
require_once("item.php");

class CartItem
{
    public static $table = "cart_item";

    public Item $item;
    public ?User $user;
    public int $user_id;
    public int $quantity;

    function __construct(Item $item, ?User $user, int $user_id, int $quantity) {
        $this->item = $item;
        $this->user_id = $user_id;
        $this->quantity = $quantity;
        $this->user = $user;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["user_id", "item_id", "quantity"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $quantity = DbManager::get_column($metadata, $row, self::$table, "quantity");
        $user_id = DbManager::get_column($metadata, $row, self::$table, "user_id");
        $item = Item::build_from_row($metadata, $row);

        if ($quantity !== null && $user_id !== null) {
            return new self($item, null, $user_id, $quantity);
        }

        throw new MissingColumnError();
        
    }
}

?>