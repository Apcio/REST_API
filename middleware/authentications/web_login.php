<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once('interfaces.php');
require_once(__DIR__ . '/../validators/web_login_validator.php');
require_once("{$_SERVER['DOCUMENT_ROOT']}/app/webConnection.php");


class CWEB_LOGINS implements IAUTH_USER, ILOGIN_USER {
    private $userLoginString;
    private $userPasswordString;
    private $sessionTokenString;
    private $isUserlogged;
    private $loginType;
    private $connection;
    /*
        $auth - when 2 parameters then login (string), password (string)
        $aut - when 1 parameter then token(string) from session
    */
    public function __construct(...$auth)
    {
        $this->clearValues();
        $this->connection = new CCONNECTION();

        if( count($auth) < 1 OR count($auth) > 2) {
            throw new Exception("Class constructor need 2 variable in argument as login and password, or 1 parameter as token from cookies");
        }

        if(count($auth) == 1) {
            $this->sessionTokenString = $auth[0];
            $this->loginType = "token";
        }

        if(count($auth) == 2) {
            $this->userLoginString = $auth[0];
            $this->userPasswordString = hash("md5", $auth[1]);
            $this->loginType = "password";
        }
    }

    public function __destruct()
    {
        $this->clearValues();
        unset($this->connection);
        if(isset($_SESSION["web_login_validation"])) {
            unset($_SESSION["web_login_validation"]);
        }
    }

    public function isUserAuthenticated(): bool {   
        return $this->isUserlogged;
    }

    public function logInUser(): bool {
        if($this->validateUserLogin() == false) {
            return false;
        }

        if($this->loginType == "password") {
            return $this->logInUserByPassword();
        }
        return false;
    }

    private function logInUserByPassword():bool {
        $query = $this->connection->getConnection()->prepare(
            "SELECT
                users.user_id,
                users.user_name,
                users.user_surname,
                users.user_web_login,
                users.user_web_password,
                users.user_dict_language_code
            FROM users
            WHERE users.user_web_login = :login"
        );

        $query->bindParam(":login", $this->userLoginString, PDO::PARAM_STR);
        if($query->execute() == false) {
            throw new Exception("Query execution failed");
        }

        if($query->rowCount() != 1) {
            $this->addErrorValidation('isUserLogged', 'Podany użytkownik nie istnieje');
            return false;
        }

        $user = $query->fetch();
        if($user["user_web_password"] != $this->userPasswordString) {
            $this->addErrorValidation('isUserLogged', 'Niepoprawne hasło');
            return false;
        }

        $_SESSION["userDB"] = $user;
        $this->isUserlogged = true;
        if(isset($_SESSION["web_login_validation"])) {
            unset($_SESSION["web_login_validation"]);
        }
        return true;
    }

    private function validateUserLogin(): bool {
        $result = true;
        try {
            $validator = new CWEB_LOGIN_VALIDATOR();
            $arr = $validator->isValid($this->userLoginString, $this->userPasswordString);
            foreach($arr AS $name => $val) {
                $this->addErrorValidation($name, $val);
            }
            $result = (count($arr) === 0);
        }
        catch(Exception $e) {
            addErrorValidation("Exception", $e->getMessage());
            $result = false;
        } finally {
            if(isset($validator)) {
                unset($validator);
            }
        }

        return $result;
    }

    private function clearValues() {
        $this->isUserlogged = false;
        $this->loginType = null;
        $this->sessionTokenString = null;
        $this->userLoginString = null;
        $this->userPasswordString = null;
    }

    private function addErrorValidation($type, $msg) {
        if(!isset($_SESSION["web_login_validation"])) {
            $_SESSION["web_login_validation"] = [];
        }
        $_SESSION["web_login_validation"][$type] = $msg;
    }
}

