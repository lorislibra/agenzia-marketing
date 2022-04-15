<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
require_once("src/entities/product.php");

class ReservationItemRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp cartItem from the row
            $reservation_item = ReservationItem::build_from_row($metadata, $row);

            // add the cart_item in the list
            $list[$reservation_item->reservation_id][$reservation_item->reservation->id] = $reservation_item;      
        }

        return $list;
    }

    // get cart_items of a user by its id
    function get_by_reservation_id(int $reservation_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation_item
        LEFT JOIN item ON item.id = reservation_item.item_id
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE reservation_item.reservation_id = :reservation_id;
        ");

        if ($stmt->execute(["reservation_id" => $reservation_id])) {
            $reservations = $this->parse_fetch($stmt);
            return $reservations;
        }

        return null;
    }
}

?>