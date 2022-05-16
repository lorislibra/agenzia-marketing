<?php

require_once("src/repositories/manager.php");
require_once("src/entities/reservation.php");
require_once("src/entities/item.php");

class ReservationItem
{
    public static $table = "reservation_item";

    public ?Item $item;
    public int $item_id;
    public ?Reservation $reservation;
    public int $reservation_id;
    public int $quantity;

    function __construct(?Item $item, int $item_id, ?Reservation $reservation, int $reservation_id, int $quantity) {
        $this->item = $item;
        $this->item_id = $item_id;
        $this->reservation_id = $reservation_id;
        $this->quantity = $quantity;
        $this->reservation = $reservation;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["user_id", "item_id", "quantity"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $quantity = DbManager::get_column($metadata, $row, self::$table, "quantity");
        $reservation_id = DbManager::get_column($metadata, $row, self::$table, "reservation_id");
        $item_id = DbManager::get_column($metadata, $row, self::$table, "item_id");
        
        try {
            $item = Item::build_from_row($metadata, $row);
        } catch (MissingColumnError $e) { 
            $item = null;
        }
        
        try {
            $reservation = Reservation::build_from_row($metadata, $row);
        } catch (MissingColumnError $e) { 
            $reservation = null;
        }

        return new self($item, $item_id, $reservation, $reservation_id, $quantity);
    }
}

?>