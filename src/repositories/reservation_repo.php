<?php

require_once("manager.php");
require_once("src/entities/reservation.php");

enum Status
{

}

class ReservationRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
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
    function get_by_id(int $id): ?Reservation
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
    function get_all(): ?array
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

    function add(int $user_id, CreateOrderDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        INSERT INTO reservation (user_id, status, sell_point_id, date_order)
        VALUES (:user_id, 0, :sell_point_id, :date_order);
        ");

        if ($stmt->execute([
            "user_id" => $user_id,
            "sell_point_id" => $dto->sell_point_id,
            "date_order" => new DateTime("now")
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

}

?>