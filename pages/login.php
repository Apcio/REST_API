<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once("{$_SERVER['DOCUMENT_ROOT']}/middleware/authentications/web_login.php");

try {
    if(isset($_SESSION["userDB"])) {
        header("Location: /pages/list.php");
        exit();
    }

    $login = null;
    $password = null;
    $token = null;

    if(isset($_POST["user_login"])) {
        $login = $_POST["user_login"];
    }

    if(isset($_POST["user_password"])) {
        $password = $_POST["user_password"];
    }

    $cLogin = null;

    if(isset($login, $password)) {
        $cLogin = new CWEB_LOGINS($login, $password);
    }

    if(isset($token)) {
        $cLogin = new CWEB_LOGINS($token);
    }

    if(isset($cLogin)) {
        //try to login user
        if($cLogin->logInUser()) {
            //login successful
            header("Location: /pages/list.php");
            exit();
        }
    }
} catch(Exception $e) {
    header("Location: /404.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Zaloguj się</title>
    </head>
    <body>

        <?php
            if(isset($_SESSION["web_login_validation"])) {
                foreach($_SESSION["web_login_validation"] AS $row)
                echo "<div>{$row}</div>";
                echo "<br>";
            }
        ?>

        <form method="POST">
            <label>Login użytkownika: 
                <input type="text" name="user_login" required maxlength="20" pattern="^([\w]|[-_!])*$" value="<?=$login?>">
            </label>
            <br>
            <label>Hasło użytkownika: 
                <input type="password" name="user_password" required maxlength="128">
            </label>
            <br>
            <button type="submit" value="Zaloguj">Zaloguj</button>
        </form>

    </body>
</html>
