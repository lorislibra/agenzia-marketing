<?php

require_once("src/repositories/manager.php");
require_once("user.php");
require_once("item.php");
require_once("sell_point.php");

class Reservation
{
    public static $table = "cart_item";

    public int $id;
    public int $user_id;
    public ?User $user;
    public int $sell_point_id;
    public ?SellPoint $sell_point;
    public DateTime $date_order;
    public DateTime $date_delivery;
    public int $status;

    function __construct(int $id, int $user_id, ?User $user, int $sell_point_id,
            ?SellPoint $sell_point, DateTime $date_order, DateTime $date_delivery, int $status)
    {
        $this->$id = $id;
        $this->$user_id = $user_id;
        $this->$user = $user;
        $this->$sell_point_id = $sell_point_id;
        $this->$sell_point = $sell_point;
        $this->$date_order= $date_order;
        $this->$date_delivery = $date_delivery;
        $this->$status = $status;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["user_id", "item_id", "quantity"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $user_id = DbManager::get_column($metadata, $row, self::$table, "user_id");
        $sell_point_id = DbManager::get_column($metadata, $row, self::$table, "sell_point_id");
        $status = DbManager::get_column($metadata, $row, self::$table, "status");
        $date_order = DbManager::get_column($metadata, $row, self::$table, "date_order");
        $date_delivery = DbManager::get_column($metadata, $row, self::$table, "date_delivery");
        try {
            $user = User::build_from_row($metadata, $row);
        } catch (MissingColumnError $e) { 
            $user = null;
        }
        try {
            $sell_point = SellPoint::build_from_row($metadata, $row);
        } catch (MissingColumnError $e) { 
            $sell_point = null;
        }

        return new self($id, $user_id, $user, $sell_point_id, $sell_point, $date_order, $date_delivery, $status);
    }
}

?>