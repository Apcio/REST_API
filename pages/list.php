<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if(!isset($_SESSION['userDB'])) {
    header("Location: /pages/login.php");
    exit();
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/middleware/web_handler.php");

$handler = new CWEB_HANDLER();

?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lista produktów</title>
    </head>
    <body>
        <p>
            Witaj 
            <?php
                echo "{$_SESSION['userDB']['user_name']} {$_SESSION['userDB']['user_surname']}";
            ?>  
            <a href="/pages/logout.php">Wyloguj się</a>
        </p>

        <br>

        <a href="/pages/create.php">Nowy wpis</a>

        <br><br>
        
        <table border="1" style="border-collapse:collapse">
            <tr>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Ilość</th>
                <th></th>
            </tr>
            
            <?php
                $products = $handler->getListOfProducts($_SESSION['userDB']['user_dict_language_code'], null);
                foreach($products->products AS $row) {
                    echo "<tr>";
                    echo "<td>{$row->product_description_name}</td>";
                    echo "<td>{$row->product_description_description}</td>";
                    echo "<td>{$row->product_price}</td>";
                    echo "<td>{$row->product_quantity}</td>";
                    echo "<td><a href='/pages/edit.php?id={$row->product_id}&lang={$row->dict_language_code}'>Edytuj</a> <a href='/pages/delete.php?id={$row->product_id}'>Usuń</a></td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </body>
</html>
