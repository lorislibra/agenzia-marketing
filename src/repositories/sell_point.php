<?php

require_once("manager.php");
require_once("src/entities/sell_point.php");
require_once("src/entities/region.php");

class SellPointRepo extends DbManager
{

    public function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp sell_point from the row
            $sell_point = SellPoint::build_from_row($metadata, $row);

            try {
                // build the temp region from the row
                $region = Region::build_from_row($metadata, $row);
            } catch (MissingColumnError $e) { 
                $region = null;
            }
            

            // if the region is in the row, make it the sell_point "region" field
            if ($region) {
                $sell_point->region = $region;
            }

            // add the sell_point in the list
            $list[$sell_point->id] = $sell_point;
        }
        
        return $list;
    }

    #region VERIFIED
    #endregion

    #region DONE
    // get a sell_point by its id
    public function get_by_id(int $id): ?SellPoint
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE sell_point.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $sell_points = $this->parse_fetch($stmt);

            // if there are more than 0 sell_points return the first
            if (count($sell_points)) {
                return $sell_points[array_key_first($sell_points)];
            }
        }
        
        return null;
    }

    // get the sell_points of a certain region by id
    public function get_by_region(int $id): array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM sell_point
        LEFT JOIN region ON sell_point.region_id = region.id
        WHERE region.id = :id;
        ");

        if ($stmt->execute(["id" => $id])) {
            $sell_points = $this->parse_fetch($stmt);

            // if there are more than 0 sell_points return the first
            if (count($sell_points)) {
                return $sell_points[array_key_first($sell_points)];
            }
        }
        
        return null;
    }

    // get all sell_points
    public function get_all(): ?array
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
    #endregion

    #region PARTIAL
    #endregion

    #region TODO
    #endregion
}

?>

