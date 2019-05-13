<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once("interfaces.php");

class CWEB_LOGIN_VALIDATOR implements IWEB_LOGIN_VALIDATOR {
    public function isValid(string $login, string $password): array {
        $res = [];

        if(strlen($login) < 1 OR strlen($login) > 20) {
            $res["user_login_length"] = "Login użytkownika powinien posiada od 1 do 20 znaków";
        }

        if(!preg_match('/^([\w]|[-_!])*$/', $login)) {
            $res["user_login_pattern"] = "W nazwie użytkownika dozwolone są nastepujące znaki: 'a-z', 'A-Z', '0-9', '-', '_' i '!'";
        }

        if(strlen($password) < 1 OR strlen($password) > 128) {
            $res["user_password_length"] = "Hasło użytkownika powinno zawierać od 1 do 128 znaków";
        }

        return $res;
    }
}