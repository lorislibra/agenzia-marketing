<?php

require_once "manager.php";
require_once "src/entities/product.php";

class ProdutRepo extends DbManager
{

    public function parse_fetch(PDOStatement $statement): array
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

    
    #region VERIFIED
    #endregion

    #region DONE
     // get a product by its sku
     public function get_by_sku(int $sku): ?Product
     {
         $stmt = $this->get_connection()->prepare("
         SELECT * FROM product
         WHERE sku=:sku;
         ");
 
         if ($stmt->execute(["sku" => $sku])) {
             $products = $this->parse_fetch($stmt);
             
             // if there are more than 0 products return the first
             if (count($products)) {
                 return $products[array_key_first($products)];
             }
         }
         
         return null;
     }
 
     // get all products
     public function get_all(): ?array
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
    #endregion

    #region PARTIAL
    #endregion

    #region TODO
    #endregion

   
}

?>