<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(!isset($_SESSION["userDB"])) {
    header("Location: /pages/login.php");
}

if(!isset($_REQUEST["id"]) OR !is_numeric($_REQUEST["id"])) {
    header("Location: /pages/login.php");
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/middleware/web_handler.php");

$handler = new CWEB_HANDLER();
try {
    $handler->deleteProduct($_REQUEST["id"]);
} catch(Exception $e) {
    // do something
} finally {
    header("Location: /pages/list.php");
}