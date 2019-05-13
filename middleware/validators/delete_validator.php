<?php

require_once('interfaces.php');
require_once('data_validator.php');

class CPRODUCTS_DELETE_VALIDATOR implements IDATA_REQUEST_VALIDATOR {
    public function isValid(stdClass $dataObject): bool {
        if(!isset($dataObject) OR !($dataObject instanceof stdClass)) {
            return false;
        }

        if(!isset($dataObject->products) OR !is_array($dataObject->products) ) {
            return false;
        }

        foreach ($dataObject->products AS $elem) {
            if(!is_object($elem)) {
                return false;
            }

            if(!isset($elem->product_id) OR !is_int($elem->product_id)) {
                return false;
            }
        }

        return true;
    }
}