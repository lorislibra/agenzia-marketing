<?php

require_once("manager.php");
require_once("src/entities/reservation.php");
require_once("src/dtos/show_orders.php");
require_once("src/dtos/update_delivery_date.php");
require_once("src/dtos/update_status.php");
require_once("src/dtos/update_comment.php");

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
        JOIN sell_point ON reservation.sell_point_id = sell_point.id
        JOIN user ON user.id = reservation.user_id
        WHERE reservation.id = :id
        ORDER BY reservation.id ASC;
        ");

        if ($stmt->execute(["id" => $id])) {
            $reservations = $this->parse_fetch($stmt);
            return $this->get_first_element($reservations);
        }

        return null;
    }

    function count(): ?int
    {
        $stmt = $this->get_connection()->prepare("
        SELECT COUNT(*) FROM reservation;
        ");

        if ($stmt->execute()) {
            return $stmt->fetchColumn();
        }

        return null;
    }

    function count_status(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT status, COUNT(status)
        FROM reservation
        GROUP BY (status);
        ");

        if ($stmt->execute()) {
            $res = array();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $res[$row[0]] = $row[1];
            }
            return $res;
        }

        return null;
    }

    function count_status_by_user_id(int $user_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT status, COUNT(status)
        FROM reservation
        WHERE user_id = :user_id
        GROUP BY (status);
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            $res = array();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $res[$row[0]] = $row[1];
            }
            return $res;

        }

        return null;
    }

    function count_by_user_id(int $user_id): ?int
    {
        $stmt = $this->get_connection()->prepare("
        SELECT COUNT(*) FROM reservation
        WHERE user_id = :user_id;
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            return $stmt->fetchColumn();
        }

        return null;
    }

    // get reservations by user id
    function get_by_user_id(int $user_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        JOIN sell_point ON sell_point.id = reservation.sell_point_id
        WHERE reservation.user_id = :user_id
        ORDER BY date_order ASC;
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            $reservations = $this->parse_fetch($stmt);
            return $reservations;
        }

        return null;
    }

    // get all reservation
    function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        JOIN user ON user.id = reservation.user_id
        JOIN sell_point ON sell_point.id = reservation.sell_point_id
        ORDER BY date_order ASC;
        ");

        if ($stmt->execute()) {
            $reservations = $this->parse_fetch($stmt);
            return $reservations;
        }
        
        return null;
    }

    function get_all_filters(ShowOrdersDto $dto): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        JOIN user ON user.id = reservation.user_id
        JOIN sell_point ON sell_point.id = reservation.sell_point_id
        ORDER BY date_order ASC
        LIMIT :offset, :limit;
        ");

        if ($stmt->execute([
            "offset" => ($dto->page-1) * $dto->per_page,
            "limit" => $dto->per_page,
        ])) {
            return $this->parse_fetch($stmt);
        }

        return null;
    }

    function get_by_user_id_filters(ShowOrdersDto $dto, int $user_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation
        JOIN user ON user.id = reservation.user_id
        JOIN sell_point ON sell_point.id = reservation.sell_point_id
        WHERE reservation.user_id = :user_id
        ORDER BY date_order ASC
        LIMIT :offset, :limit;
        ");

        if ($stmt->execute([
            "offset" => ($dto->page-1) * $dto->per_page,
            "limit" => $dto->per_page,
            "user_id" => $user_id
        ])) {
            return $this->parse_fetch($stmt);
        }

        return null;
    }

    function add(int $user_id, CreateOrderDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        INSERT INTO reservation (user_id, status, sell_point_id, date_order, date_delivery, comment)
        VALUES (:user_id, 1, :sell_point_id, NOW(), null, '');
        ");

        if ($stmt->execute([
            "user_id" => $user_id,
            "sell_point_id" => $dto->sell_point_id
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    function update_comment(UpdateCommentDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        UPDATE reservation SET comment = :comment WHERE id = :id;
        ");

        if ($stmt->execute([
            "id" => $dto->reservation_id,
            "comment" => $dto->comment
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    function update_delivery_date(UpdateDeliveryDateDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        UPDATE reservation SET date_delivery = :date_delivery WHERE id = :id;
        ");

        if ($stmt->execute([
            "id" => $dto->reservation_id,
            "date_delivery" => $dto->delivery_date
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    function update_status(UpdateStatusDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        UPDATE reservation SET status = :status WHERE id = :id;
        ");

        if ($stmt->execute([
            "id" => $dto->reservation_id,
            "status" => $dto->status->value
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

}

?>