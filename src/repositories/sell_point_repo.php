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

    function get_all_by_regions(array $regions): ?array
    {
        $regions_list = join(",", array_map(fn (Region $region) => $region->id , $regions));

        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE sell_point.region_id IN ($regions_list);
        ");

        if ($stmt->execute()) {
            $sell_points = $this->parse_fetch($stmt);
            return $sell_points;
        }

        return null;
    }

    // get the sell_points of a certain region by id if similar to the string
    function get_by_region_and_search(int $id, string $search_string): ?array
    {
        $search_string = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $search_string);
        $words = explode(" ", $search_string);

        $query = "
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE sell_point.region_id = :region_id AND(";
        foreach($words as &$word)
        {
            $query += "sell_point.address LIKE \"%$word%\"
                    OR sell_point.name LIKE \"%$word%\"";
        }

        $stmt = $this->get_connection()->prepare($query + ")");
        
        if ($stmt->execute(["region_id" => $id])) {
            $sell_points = $this->parse_fetch($stmt);
            return $sell_points;
        }
        
        return null;
    }
}

?>

