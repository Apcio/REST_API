<?php

require_once (__DIR__ . '/../interface.php');

class CREAD implements IREAD {
    private $products;
    private $paramArray;

    public function __construct()
    {
        $this->products = new StdClass();
        $this->products->products = [];
        $this->paramArray = [];
    }

    public function __destruct()
    {
        unset($this->products->products);
        unset($this->products);
        unset($this->paramArray);
    }

    public function readData(array $params): stdClass {
        $this->getProductParams($params);

        if ( !isset($this->paramArray["lang"]) OR trim($this->paramArray["lang"] == "")) {
            $this->getDefaultLang();
        }
        $this->getProducts();

        return $this->products;
    }

    public function webReadData(array $params): stdClass {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/app/webConnection.php");
        $pdo = new CCONNECTION();
        $_SESSION["database"] = $pdo->getConnection();
        $data = $this->readData($params);
        unset($pdo);
        unset($_SESSION["database"]);
        return $data;
    }

    private function getProducts() {
        //find product in db
        
        if ( isset($this->paramArray["id"]) AND is_numeric($this->paramArray["id"]) == true) {
            $selectedID = "AND products_descriptions.product_id = :id";
        } else {
            $selectedID = "";
        }

        $query = $_SESSION["database"]->prepare(
            "SELECT
                products_descriptions.dict_language_code,
                products_descriptions.product_description_name,
                products_descriptions.product_description_description,
                products.product_id,
                products.product_price,
                products.product_quantity
            FROM myRestApi.products_descriptions
            JOIN myRestApi.products ON products.product_id = products_descriptions.product_id
            WHERE products_descriptions.dict_language_code = :code {$selectedID}"
        );
        $query->bindParam(":code", $this->paramArray["lang"], PDO::PARAM_STR);
        if ($selectedID != "") {
            $query->bindParam(":id", $this->paramArray["id"], PDO::PARAM_INT);
        }
        if ($query->execute() == false) {
            //if execution failed then throw exception
            throw new Exception('Query execution failed');
        }
        
        while ($row = $query->fetch()) {
            $product = new StdClass();

            foreach($row AS $name => $val) {
                $product->{$name} = $val; 
            }

            array_push($this->products->products, $product);
            unset($product);
        }
    }

    private function getProductParams(array $param) {
        //check if parameter is an associate array
        if (isset($param) AND is_array($param)) {
            $this->paramArray = $param;
        }
    }

    private function getDefaultLang() {
        //if user does not specify a language in query, take it from db
        $this->paramArray += array("lang" => $_SESSION['userDB']['user_dict_language_code']);
    }
}

$_SESSION["API_FUNC_INSTANCE"] = new CREAD();