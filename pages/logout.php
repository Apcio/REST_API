<?php

if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(isset($_SESSION["userDB"])) {
    unset($_SESSION["userDB"]);
}

header("Location: /pages/login.php");