<?php

require_once('interfaces.php');
require_once("{$_SERVER['DOCUMENT_ROOT']}/app/dbConnection.php");

class CUSER_AUTH_REST_API implements IAUTH_USER {
    private $userAuthenticated;
    private $userPDO;

    public function __construct($ApiToken, $ApiPublicKey) {
        $this->userAuthenticated = $this->authenticateUser($ApiToken, $ApiPublicKey);    
    }

    public function isUserAuthenticated(): bool {
        return $this->userAuthenticated;
    }

    private function authenticateUser($token, $publicKey) {
        $privateKey = $this->findPrivateToken($publicKey);

        if ($privateKey == false) {
            return false;
        }

        if ($this->generateToken($publicKey, $privateKey) != $token) {
            return false;
        }

        //token is verified 
        $_SESSION['userDB'] = $this->userPDO;

        return true;
    }

    private function findPrivateToken($publicKey) {
        //find private token in Database
        $query = $_SESSION["database"]->prepare(
            "SELECT
                user_id,
                user_name,
                user_surname,
                user_private_key,
                user_public_key,
                user_dict_language_code
            FROM myRestApi.users
            WHERE myRestApi.users.user_public_key = :id",
            array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)
        );
        $query->bindParam(':id', $publicKey, PDO::PARAM_STR);
        if ($query->execute() == false) {
            return false;
        }
        //Public key is unique, so at least one record should be returned
        $this->userPDO = $query->fetch();

        if (!isset($this->userPDO) OR $this->userPDO == false) {
            return false;
        }

        if ($publicKey == $this->userPDO['user_public_key']) {
            return $this->userPDO['user_private_key'];
        }
        
        return false;
    }

    private function generateToken($publicKey, $privateKey) {
        //generate hash MD5
        return md5($publicKey.$privateKey);
    }
}
