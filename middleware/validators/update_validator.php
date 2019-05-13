<?php

require_once('interfaces.php');
require_once('data_validator.php');

class CPRODUCTS_UPDATE_VALIDATOR extends CDATA_VALIDATOR implements IDATA_REQUEST_VALIDATOR {
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

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

            if(isset($elem->dict_language_code) AND !in_array($elem->dict_language_code, $this->languages)) {
                return false;
            }

            if(isset($elem->product_desctiption_description) AND !is_string($elem->product_desctiption_description)) {
                return false;
            }

            if(isset($elem->product_description_name) AND !is_string($elem->product_description_name)) {
                return false;
            }

            if(isset($elem->product_quantity) AND !is_numeric($elem->product_quantity)) {
                return false;
            }

            if(isset($elem->product_price) AND !is_numeric($elem->product_price)) {
                return false;
            }

            if( (isset($elem->product_description_name) OR isset($elem->product_desctiption_description)) AND !isset($elem->dict_language_code) ) {
                return false;
            }
        }

        return true;
    }
}