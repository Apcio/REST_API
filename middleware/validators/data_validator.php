<?php

class CDATA_VALIDATOR {
    protected $languages;

    public function __construct()
    {
        $this->languages = [];
        $this->getLanguageCodes();
    }

    public function __destruct()
    {
        unset($this->languages);
    }

    protected function getLanguageCodes() {
        $query = $_SESSION["database"]->prepare (
            "SELECT dict_language_code FROM myRestApi.dict_languages"
        );
        if ($query->execute() == false) {
            throw new Exception('Query execution failed');
        }

        while ($row = $query->fetch(PDO::FETCH_NUM)) {
            array_push($this->languages, $row[0]);
        }
    }

}