<?php

require_once (__DIR__ . '/../interface.php');
require_once ("{$_SERVER['DOCUMENT_ROOT']}/middleware/validators/update_validator.php");

class CUPDATE implements IUPDATE {
    public function updateData(stdClass $data): stdclass {
        $validator = new CPRODUCTS_UPDATE_VALIDATOR();
        if(!$validator->isValid($data)) {
            throw new API_EXCEPTION('Wrong JSON object in data send');
        }

        try {
            $_SESSION["database"]->beginTransaction();
            foreach($data->products AS $row){
                $this->verifyActions($row);
            }
            $_SESSION["database"]->commit();
        }
        finally {
            if($_SESSION["database"]->inTransaction()) {
                $_SESSION["database"]->rollback();
            }
        }
        
        return $data;
    }

    public function webUpdateData(stdClass $data): stdClass {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/app/webConnection.php");
        $pdo = new CCONNECTION();
        $_SESSION["database"] = $pdo->getConnection();
        $result = $this->updateData($data);
        unset($pdo);
        unset($_SESSION["database"]);
        return $result;
    }

    private function verifyActions($product) {
        $product->update = new stdClass;

        $query = $_SESSION["database"]->prepare(
            "SELECT
                products.product_id,
                products_descriptions.product_description_id
            FROM products
            LEFT JOIN products_descriptions ON products_descriptions.product_id = products.product_id AND products_descriptions.dict_language_code = :dict_language_code
            WHERE products.product_id = :product_id"
        );
        $query->bindParam(":product_id", $product->product_id, PDO::PARAM_INT);
        $query->bindParam(":dict_language_code", $product->dict_language_code);
        if(!$query->execute()) {
            throw new Exception('Query execution failed');
        }

        $result = $query->fetch();
        if(!isset($result["product_id"])) {
            $product->update->error = "Product does not exists";
            return;
        }

        if(isset($product->product_price) OR isset($product->product_quantity)){
            $this->updateStorehouse($product);
            $product->update->storeHouse = "Updated";
        }

        if( (isset($product->product_description_name) OR isset($product->product_description_description) ) AND isset($result["product_description_id"]) ) {
            $this->updateDetails($product, $result);
            $product->update->details = "Updated";
        }

        if( (isset($product->product_description_name) OR isset($product->product_description_description) ) AND !isset($result["product_description_id"]) ) {
            $this->insertDetail($product);
            $product->update->details = "Inserted";
        }
    
    }

    private function updateStorehouse($product) {
        $columns = "";
        if(isset($product->product_price)) {
            if($columns != "") {
                $columns .= ",";
            }
            $columns .= "product_price = :product_price";
        }

        if(isset($product->product_quantity)) {
            if($columns != "") {
                $columns .= ",";
            }
            $columns .= "product_quantity = :product_quantity";
        }

        $query = $_SESSION["database"]->prepare(
            "UPDATE products SET
                {$columns}
            WHERE product_id = :product_id"
        );
        
        $query->bindParam(":product_id", $product->product_id, PDO::PARAM_INT);
        if(isset($product->product_price)) {
            $query->bindParam(":product_price", $product->product_price);
        }
        if(isset($product->product_quantity)) {
            $query->bindParam(":product_quantity", $product->product_quantity);
        }

        if($query->execute() == false)
        {
            throw new Exception('Query execution failed');
        }
    }

    private function updateDetails($product, $ids) {
        $columns = "";
        if(isset($product->product_description_name)) {
            if($columns != "") {
                $columns .= ",";
            }
            $columns .= "product_description_name = :product_description_name";
        }

        if(isset($product->product_description_description)) {
            if($columns != "") {
                $columns .= ",";
            }
            $columns .= "product_description_description = :product_description_description";
        }

        $query = $_SESSION["database"]->prepare(
            "UPDATE products_descriptions SET
                {$columns}                
            WHERE product_description_id = :product_description_id"
        );
        
        $query->bindParam(":product_description_id", $ids["product_description_id"], PDO::PARAM_INT);
        if(isset($product->product_description_name)) {
            $query->bindParam(":product_description_name", $product->product_description_name);
        }
        if(isset($product->product_description_description)) {
            $query->bindParam(":product_description_description", $product->product_description_description);
        }

        if($query->execute() == false)
        {
            throw new Exception('Query execution failed');
        }
    }

    private function insertDetail($product) {
        $query = $_SESSION["database"]->prepare(
            "INSERT INTO products_descriptions(product_id, dict_language_code, product_description_name, product_description_description)
            VALUES(:product_id, :dict_language_code, :product_description_name, :product_description_description);"
        );

        $query->bindParam(":product_id", $product->product_id, PDO::PARAM_INT);
        $query->bindParam(":dict_language_code", $product->dict_language_code, PDO::PARAM_STR);
        $query->bindParam(":product_description_name", $product->product_description_name, PDO::PARAM_STR);
        $query->bindParam(":product_description_description", $product->product_description_description, PDO::PARAM_STR);

        if($query->execute() == false)
        {
            throw new Exception('Query execution failed');
        }
    }
}

$_SESSION["API_FUNC_INSTANCE"] = new CUPDATE();