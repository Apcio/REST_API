<?php

require_once (__DIR__ . '/../interface.php');
require_once ("{$_SERVER['DOCUMENT_ROOT']}/middleware/validators/delete_validator.php");

class CDELETE implements IDELETE {
    public function deleteData(stdClass $data): stdClass {
        $validator = new CPRODUCTS_DELETE_VALIDATOR();
        if(!$validator->isValid($data)) {
            throw new API_EXCEPTION('Wrong JSON object in data send');
        }

        try {
            $_SESSION["database"]->beginTransaction();

            foreach($data->products AS $row) {
                $this->delete($row);
            }

            $_SESSION["database"]->commit();

        }
        finally {
            if($_SESSION["database"]->inTransaction()) {
                $_SESSION["database"]->rollback();
            }
        }

        return json_decode('{"deleted":"OK"}');
    }

    public function webDeleteData(stdClass $data): stdClass {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/app/webConnection.php");
        $pdo = new CCONNECTION();
        $_SESSION["database"] = $pdo->getConnection();
        $return = $this->deleteData($data);
        unset($pdo);
        unset($_SESSION["database"]);
        return $return;
    }

    private function delete($productData) {
        $query = $_SESSION["database"]->prepare(
            "DELETE FROM products WHERE product_id = :product_id"
        );
        $query->bindParam(":product_id", $productData->product_id, PDO::PARAM_INT);

        if(!$query->execute()) {
            throw new Exception('Query execution failed');
        }
    }
}

$_SESSION["API_FUNC_INSTANCE"] = new CDELETE();