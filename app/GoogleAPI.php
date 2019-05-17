<?php


namespace app;

use Google_Client;
use Google_Service_Oauth2;

class GoogleAPI {

    private $googleClient;

    public function __construct() {
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId("934209881849-vpj4r552e0rfbp4v9kbsm9ontdcic4aq.apps.googleusercontent.com");
        $this->googleClient->setClientSecret("VjQWezEcBTOet_JrFiTFLtjP");
        $this->googleClient->setApplicationName("Seminárka 4IZ278 novp19");
        $this->googleClient->setRedirectUri("https://eso.vse.cz/~novp19/google/login/");
        $this->googleClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
    }

    public function getAuthUrl() {
        return $this->googleClient->createAuthUrl();
    }

    public function getUserData($code) {
        $token = @$this->googleClient->fetchAccessTokenWithAuthCode($code);
        $_SESSION['access_token'] = $token;
        $oAuth = new Google_Service_Oauth2($this->googleClient);
        $userData = $oAuth->userinfo_v2_me->get();
        return $userData;
    }
}