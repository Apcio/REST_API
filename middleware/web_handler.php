<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION["userDB"])) {
    header("Location: /pages/login.php");
    exit();
}

require_once($_SERVER["DOCUMENT_ROOT"] . '/app/apiException.php');

require_once("{$_SERVER['DOCUMENT_ROOT']}/api/products/read.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/api/products/delete.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/api/products/update.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/api/products/create.php");

class CWEB_HANDLER {
    public function getListOfProducts($dict_language_code, $product_id): stdClass {
        $param = array();
        if(!isset($dict_language_code)) {
            $param["lang"] = $_SESSION["userDB"]["dict_language_code"];
        } else {
            $param["lang"] = $dict_language_code;
        }

        if(isset($product_id)) {
            $param["id"] = $product_id;
        }

        $products = new CREAD();
        $result = clone($products->webReadData($param));
        return $result;
    }

    public function deleteProduct($id) {
        $product = new stdClass();
        $product->products = [];
        $elem = new stdClass();
        $elem->product_id = intval($id);
        array_push($product->products, $elem);

        $del = new CDELETE();
        $del->webDeleteData($product);
    }

    public function updateProduct(stdClass $product) {
        $upd = new CUPDATE();
        return $upd->webUpdateData($product);
    }

    public function createProduct(stdClass $product) {
        $crd = new CCREATE();
        return $crd->webCreateData($product);
    }
}
