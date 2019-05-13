<?php

require_once (__DIR__ . '/../interface.php');
require_once ("{$_SERVER['DOCUMENT_ROOT']}/middleware/validators/create_validator.php");

class CCREATE implements ICREATE {
    public function createData(stdClass $data): stdClass {
        $validator = new CPRODUCTS_CREATE_VALIDATOR();
        if ($validator->isValid($data) == false) {
            throw new API_EXCEPTION('Wrong JSON object in data send');
        }

        $_SESSION["database"]->beginTransaction();
        try {
            foreach($data->products AS $row) {
                $query = $_SESSION["database"]->prepare(
                    "INSERT INTO products (product_price, product_quantity)
                    VALUES(:price, :quant)"
                );

                $query->bindParam(':price', $row->product_price);
                $query->bindParam(':quant', $row->product_quantity);
                if (!$query->execute()) {
                    throw new Exception('Query execution failed');
                }

                $id = $_SESSION["database"]->lastInsertId();
            
                $query = $_SESSION["database"]->prepare(
                    "INSERT INTO products_descriptions(product_id, dict_language_code, product_description_name, product_description_description)
                    VALUES( :id, :lang, :title, :descript)"
                );
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->bindParam(':lang', $row->dict_language_code);
                $query->bindParam(':title', $row->product_description_name);
                $query->bindParam(':descript', $row->product_description_description);
                if (!$query->execute()) {
                    throw new Exception('Query execution failed');
                }
                
                $row->product_id = $id;
            }

            $_SESSION["database"]->commit();
            return $data;
        }
        finally {
            if ($_SESSION["database"]->inTransaction()) {
                $_SESSION["database"]->rollback();
            }
        }
    }

    public function webCreateData(stdClass $data) {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/app/webConnection.php");
        $pdo = new CCONNECTION();
        $_SESSION["database"] = $pdo->getConnection();
        $result = $this->createData($data);
        unset($pdo);
        unset($_SESSION["database"]);
        return $result;
    }
}

$_SESSION["API_FUNC_INSTANCE"] = new CCREATE();