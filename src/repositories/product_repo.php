<?php

require_once("manager.php");
require_once("src/entities/product.php");

class ProdutRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp product from the row
            $product = Product::build_from_row($metadata, $row);

            // add the product in the list
            $list[$product->sku] = $product;
            
        }
        return $list;
    }

    
    // get a product by its sku
    function get_by_sku(int $sku): ?Product
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM product
        WHERE sku=:sku;
        ");

        if ($stmt->execute(["sku" => $sku])) {
            $products = $this->parse_fetch($stmt);
            $this->get_first_element($products);
        }
        
        return null;
    }

    // get all products
    function get_all(): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM product;
        ");

        if ($stmt->execute()) {
            $products = $this->parse_fetch($stmt);
            return $products;
        }

        return null;
    }
   
}

?>