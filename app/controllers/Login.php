<?php


namespace app\controllers;


class Login extends Controller {

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('login/index');
    }
}