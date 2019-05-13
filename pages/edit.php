<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION['userDB'])) {
    header("Location: /pages/login.php");

    if(isset($_SESSION["update_product"])) {
        unset($_SESSION["update_product"]);
    }

    exit();
}

if(!isset($_REQUEST["id"]) OR !is_numeric($_REQUEST["id"])) {
    header("Location: /pages/list.php");

    if(isset($_SESSION["update_product"])) {
        unset($_SESSION["update_product"]);
    }

    exit();
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/middleware/web_handler.php");

try {
    $handler = new CWEB_HANDLER();
    $lang = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : $_SESSION["userDB"]["user_dict_language_code"];
    $id = intval($_REQUEST["id"]);

    if(count($_POST) == 0) {
        $_SESSION["update_product"] = $handler->getListOfProducts($lang, $id);
    }

    if(count($_SESSION["update_product"]->products) != 1) {
        header("Location: /pages/list.php");
        
        if(isset($_SESSION["update_product"])) {
            unset($_SESSION["update_product"]);
        }
        
        exit();
    }

    $currentProduct = $_SESSION["update_product"]->products[0];

    if(count($_POST) > 0) {
        $currentProduct->product_description_name = $_POST["product_description_name"];
        $currentProduct->product_description_description = $_POST["product_description_description"];
        $currentProduct->product_price = $_POST["product_price"];
        $currentProduct->product_quantity = $_POST["product_quantity"];

        $handler->updateProduct($_SESSION["update_product"]);

        unset($_SESSION["update_product"]);
        header("Location: /pages/list.php");
        exit();

    }
} catch(Exception $e) {
    //do something
    if(isset($_SESSION["update_product"])) {
        unset($_SESSION["update_product"]);
    }
    header("Location: /pages/list.php");
    exit();
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Modyfikuj produkt <?= $currentProduct->product_description_name ?></title>
    </head>
    <body>
        <a href="/pages/list.php">Anuluj</a>
        <br><br>
        <form method="POST">
            <label>Nazwa:<br>
                <input type="text" maxlength="150" required name="product_description_name" value="<?= $currentProduct->product_description_name ?>" />
            </label>
            <br><br>
            <label>Opis:<br>
            <textarea rows="5" cols="50" required name="product_description_description"><?= $currentProduct->product_description_description ?></textarea>
            </label>
            <br><br>
            <label>Cena<br>
                <input type="number" min="0" step="0.01" name="product_price" value="<?= $currentProduct->product_price ?>" />
            </label>
            <br><br>
            <label>Ilość<br>
                <input type="number" name="product_quantity" value="<?= $currentProduct->product_quantity ?>" />
            </label>
            <br><br><br>
            <button type="submit">Aktualizuj</button>
        </form>
    </body>
</html>
