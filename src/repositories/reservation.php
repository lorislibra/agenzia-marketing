<?php

require_once("manager.php");
require_once("src/entities/reservation.php");

class Reservation extends DbManager
{

    public function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp reservation from the row
            $reservation = Reservation::build_from_row($metadata, $row);

            // add the reservation in the list
            $list[$reservation->id] = $reservation;           
        }

        return $list;
    }
    
    // get a reservation by its id
    public function get_by_id(int $id): ?Item
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * 
        FROM reservation
        WHERE reservation.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $items = $this->parse_fetch($stmt);

            // if there are more than 0 items return the first
            if (count($items)) {
                return $items[array_key_first($items)];
            }
        }
        
        return null;
    }

}

?>