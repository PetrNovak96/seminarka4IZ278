<?php


namespace app;


class CurrentUser {

    private static $instance;

    public $email;
    public $id;

    private function __construct(){}

    public static function login($email, $id) {
        $currentUser = new CurrentUser();
        $currentUser->email = $email;
        $currentUser->id = $id;
        $_SESSION['userId'] = $id;
        $_SESSION['email'] = $email;
        self::$instance= &$currentUser;
        header('Location: /~novp19/index');
        exit();
    }

    public static function getInstance() {
        if (!self::$instance) {
            $currentUser = new CurrentUser();
            $currentUser->email = $_SESSION['email'];
            $currentUser->id = $_SESSION['userId'];
            self::$instance= &$currentUser;
        }
        return self::$instance;
    }

    public static function logout() {
        self::$instance = null;
        session_destroy();
        header('Location: /~novp19/login/loggedOut/');
    }

    /**
     * Funkce pro kontrolu, jestli je uživatel přihlášen
     */
    public function isLoggedIn(){
        return $this->id > 0;
    }
}