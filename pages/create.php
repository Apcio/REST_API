<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION['userDB'])) {
    header("Location: /pages/login.php");

    exit();
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/middleware/web_handler.php");

try {
    if(count($_POST) > 0) {
        $handler = new CWEB_HANDLER();

        $products = new stdClass();
        $products->products = [];

        $elem = new stdClass();
        $elem->product_description_name = $_POST["product_description_name"];
        $elem->product_description_description = $_POST["product_description_description"];
        $elem->product_price = $_POST["product_price"];
        $elem->product_quantity = $_POST["product_quantity"];
        $elem->dict_language_code = $_SESSION["userDB"]["user_dict_language_code"];

        array_push($products->products, $elem);
        $handler->createProduct($products);

        header("Location: /pages/list.php");
        exit();
    }
    
    
} catch(Exception $e) {
    //do something

    header("Location: /pages/list.php");
    exit();
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Nowy produkt</title>
    </head>
    <body>
        <a href="/pages/list.php">Anuluj</a>
        <br><br>
        <form method="POST">
            <label>Nazwa:<br>
                <input type="text" maxlength="150" required name="product_description_name"/>
            </label>
            <br><br>
            <label>Opis:<br>
            <textarea rows="5" cols="50" required name="product_description_description"></textarea>
            </label>
            <br><br>
            <label>Cena<br>
                <input type="number" min="0" step="0.01" name="product_price" />
            </label>
            <br><br>
            <label>Ilość<br>
                <input type="number" name="product_quantity" />
            </label>
            <br><br><br>
            <button type="submit">Wprowadź</button>
        </form>
    </body>
</html>
