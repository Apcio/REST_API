<?php

header('Content-Type: application/json; charset=utf-8');
header('Connection: close');

class RESPONSE_API {
    public function error($val) {
        http_response_code(400);
        $res = json_encode(array('error' => $val), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        //$size = strlen($res);
        //header("Content-Lenght: $size");
        return $res;
    }

    public function serverError($val){
        http_response_code(500);
        $res = json_encode(array('error' => $val), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        //$size = strlen($res);
        //header("Content-Lenght: $size");
        return $res;
    }

    public function data($val){
        http_response_code(200);
        $res = json_encode($val, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        //$size = strlen($res);
        //header("Content-Lenght: $size");
        return $res;
    }
}