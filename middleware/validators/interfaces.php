<?php

interface IURL_VALIDATOR {
    public function isValid($url): bool;
}

interface IDATA_REQUEST_VALIDATOR {
    public function isValid(stdClass $dataObject): bool;
}

interface IWEB_LOGIN_VALIDATOR {
    public function isValid(string $login, string $password): array;
}