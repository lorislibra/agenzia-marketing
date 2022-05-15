<?php

require_once("src/repositories/manager.php");
require_once("src/entities/user.php");
require_once("src/entities/item.php");
require_once("src/entities/sell_point.php");

enum OrderStatus: int
{
    case Waiting = 1;
    case Approved = 2;
    case Shipping = 3;
    case Arrived = 4;

    function string(): string
    {
        switch ($this) {
            case self::Waiting:
                return "Waiting";
                break;
            case self::Approved:
                return "Approved";
                break;
            case self::Shipping:
                return "Shipping";
                break;
            case self::Arrived:
                return "Arrived";
                break;
        }
    }    
}

class Reservation
{
    public static $table = "reservation";

    public int $id;
    public int $user_id;
    public ?User $user;
    public string $comment;
    public int $sell_point_id;
    public ?SellPoint $sell_point;
    public DateTime $date_order;
    public ?DateTime $date_delivery;
    public OrderStatus $status;

    function __construct(
        int $id, int $user_id, ?User $user, int $sell_point_id, ?SellPoint $sell_point, string $comment,  
        DateTime $date_order, ?DateTime $date_delivery, OrderStatus $status
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->user = $user;
        $this->comment = $comment;
        $this->sell_point_id = $sell_point_id;
        $this->sell_point = $sell_point;
        $this->date_order= $date_order;
        $this->date_delivery = $date_delivery;
        $this->status = $status;
    }

    public static function check_row_column(QueryMetadata $metadata): bool 
    {
        return !$metadata->exists(self::$table, ["id", "user_id", "sell_point_id", "date_order", "date_delivery", "status"]);
    }

    public static function build_from_row(QueryMetadata $metadata, array $row): self 
    {
        $id = DbManager::get_column($metadata, $row, self::$table, "id");
        $user_id = DbManager::get_column($metadata, $row, self::$table, "user_id");
        $comment = DbManager::get_column($metadata, $row, self::$table, "comment");
        $sell_point_id = DbManager::get_column($metadata, $row, self::$table, "sell_point_id");
        $status = DbManager::get_column($metadata, $row, self::$table, "status");
        $date_order = DbManager::get_column($metadata, $row, self::$table, "date_order");
        
        try { $date_delivery = DbManager::get_column($metadata, $row, self::$table, "date_delivery"); } catch (MissingColumnError $e) { $date_delivery = null; } 
        try { $user = User::build_from_row($metadata, $row); } catch (MissingColumnError $e) { $user = null; }
        try { $sell_point = SellPoint::build_from_row($metadata, $row); } catch (MissingColumnError $e) {  $sell_point = null; }

        $status = OrderStatus::from($status);

        return new self($id, $user_id, $user, $sell_point_id, $sell_point, $comment, new DateTime($date_order), $date_delivery ? new DateTime($date_delivery) : null, $status);
    }
}

?>