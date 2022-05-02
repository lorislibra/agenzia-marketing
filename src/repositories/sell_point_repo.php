<?php

require_once("manager.php");
require_once("src/entities/sell_point.php");
require_once("src/entities/region.php");

class SellPointRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            // build the temp sell_point from the row
            $sell_point = SellPoint::build_from_row($metadata, $row);

            // add the sell_point in the list
            $list[$sell_point->id] = $sell_point;
        }
        
        return $list;
    }

    // get a sell_point by its id
    function get_by_id(int $id): ?SellPoint
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE sell_point.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $sell_points = $this->parse_fetch($stmt);
            return $this->get_first_element($sell_points);
        }
        
        return null;
    }

    // get the sell_points of a certain region by id
    function get_by_region(int $id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE region.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $sell_points = $this->parse_fetch($stmt);
            return $sell_points;
        }
        
        return null;
    }

    // get all sell_points
    function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id;
        ");

        if ($stmt->execute()) {
            $sell_points = $this->parse_fetch($stmt);
            return $sell_points;
        }

        return null;
    }
}

?>

