<?php

require_once('interfaces.php');

class CURL_VALIDATE implements IURL_VALIDATOR {
    private $urlAsArray;

    public function isValid($url): bool {
      $this->urlAsArray = explode('/', Filter_var($url, FILTER_SANITIZE_URL));

      if ( !in_array($_SERVER["REQUEST_METHOD"], array("GET", "POST", "PUT", "DELETE") )) {
          return false;
      }

      //składnia API: api/klucz publiczny/TOKEN/nazwa funkcji/opcjonalne
      if (sizeof($this->urlAsArray) < 5) {
          return false;
      }

      //czy zaczyna się od api
      if ($this->urlAsArray[1] != 'api') {
          return false;
      }

      //klucz publiczny ma przynajmniej 16 znaków
      if ( strlen($this->urlAsArray[2]) < 16 ) {
        return false;
      }

      //TOKEN składa się z przynajmniej 32 znaków
      if( strlen($this->urlAsArray[3]) < 32 )
      {
          return false;
      }

      //czy jest funkcja w api?
      if (!in_array($this->getUrlFunctionWithoutParams(), $this->getAvailableFunctions())) {
          return false;
      }

      return true;
    }

    private function getAvailableFunctions() {
        return array_diff(scandir($_SERVER["DOCUMENT_ROOT"] . '/api'), array('.', '..'));
    }

    private function getUrlFunctionWithoutParams() {
        $znak = strpos($this->urlAsArray[4], "?");
        if ($znak == false) {
            $znak = strlen($this->urlAsArray[4]);
        }

        $_SESSION['API_SELECTED_FUNCTION'] = substr($this->urlAsArray[4], 0, $znak);
        return $_SESSION['API_SELECTED_FUNCTION'];
    }
}
