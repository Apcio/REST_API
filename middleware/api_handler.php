<?php

//error_reporting(0);
//ini_set('display_errors', 1);

require_once($_SERVER["DOCUMENT_ROOT"] . '/app/apiException.php');
require_once('validators/url_validate.php');
require_once('authentications/users.php');
require_once('response.php');

$response = new RESPONSE_API();

try {
    $handler = new CURL_VALIDATE();

    if(!$handler->isValid($_SERVER["REQUEST_URI"])) {
        throw new API_EXCEPTION('Bad URL');
    }
    
    $url = explode('/', Filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL));
    $handler = new CUSER_AUTH_REST_API($url[3], $url[2]);
    
    if (!$handler->isUserAuthenticated()) {
        throw new API_EXCEPTION('Authentication failed');
    }

    if ($_SERVER["REQUEST_METHOD"] == 'GET') {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/api/{$_SESSION['API_SELECTED_FUNCTION']}/read.php");
        $parameters = [];
        parse_str(urldecode($_SERVER["QUERY_STRING"]), $parameters);
        echo $response->data($_SESSION["API_FUNC_INSTANCE"]->readData($parameters));
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/api/{$_SESSION['API_SELECTED_FUNCTION']}/create.php");
        
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        
        if (!isset($data)) {
            $data = new stdClass;
        }
        echo $response->data($_SESSION["API_FUNC_INSTANCE"]->createData($data));
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == 'PUT') {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/api/{$_SESSION['API_SELECTED_FUNCTION']}/update.php");
        
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        
        if (!isset($data)) {
            $data = new stdClass;
        }
        echo $response->data($_SESSION["API_FUNC_INSTANCE"]->updateData($data));
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == 'DELETE') {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/api/{$_SESSION['API_SELECTED_FUNCTION']}/delete.php");
        
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        
        if (!isset($data)) {
            $data = new stdClass;
        }
        echo $response->data($_SESSION["API_FUNC_INSTANCE"]->deleteData($data));
        exit();
    }

    throw new Exception('Unknown');
}
catch (API_EXCEPTION $e) {
    echo $response->error($e->getMessage());
}
catch (Exception $e) {
    echo $response->serverError('Server error, please try later');
}