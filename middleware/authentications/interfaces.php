<?php

interface IAUTH_USER {
    public function isUserAuthenticated(): bool;   
}

interface ILOGIN_USER {
    public function logInUser(): bool;
}