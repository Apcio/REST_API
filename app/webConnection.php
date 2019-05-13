<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

class CCONNECTION {
    private $pdo;
    public function __construct()
    {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/config/database.php");

        $this->pdo = new PDO("mysql:host={$dbHost};dbname={$database};port{$dbPort}", $dbUser, $dbPassword,
            array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
        );
    }

    public function __destruct()
    {
        unset($this->pdo);
    }

    public function getConnection() {
        return $this->pdo;
    }
}