<?php

require_once("manager.php");
require_once("src/entities/reservation.php");

enum Status
{
}

class ReservationRepo extends DbManager
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
    public function get_by_id(int $id): ?Reservation
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        WHERE reservation.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $reservations = $this->parse_fetch($stmt);
            return $this->get_first_element($reservations);
        }
        
        return null;
    }

    // get a reservation by its id
    public function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        ");

        if ($stmt->execute()) {
            $reservations = $this->parse_fetch($stmt);
            return $reservations;
        }
        
        return null;
    }

}

?>