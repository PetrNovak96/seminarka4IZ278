<?php


namespace app\controllers;


use app\model\UsersModel;

class Login extends Controller {

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('login/index');
    }

    function loggedOut() {
        $this->view->info = 'Odhlášení proběhlo úspěšně.';
        $this->view->render('login/index');
    }

    function forgotten() {
        $this->view->render('login/forgotten');
    }

    function change($parameters) {
        $email = $parameters[0];
        $hash = $parameters[1];
        $usersModel = new UsersModel();
        if ($usersModel->isValidFPHash($email, $hash)) {
            $this->view->email = $email;
            $this->view->render('login/change');
        }

    }
}