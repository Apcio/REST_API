<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/config/database.php");

if (!isset($_SESSION["database"])) {
    $_SESSION["database"] = new PDO("mysql:host={$dbHost};dbname={$database}", $dbUser, $dbPassword,
        array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        )
    );
} 
