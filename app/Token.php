<?php


namespace app;


class Token
{
    public static function generate() {
        unset($_SESSION['token']);
        $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
        return $_SESSION['token'];
    }

    public static function check($token) {
        if (isset($_SESSION['token']) && $token == $_SESSION['token']) {
            unset($_SESSION['token']);
            return true;
        }
        return false;
    }
}