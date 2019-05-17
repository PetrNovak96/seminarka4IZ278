<?php


namespace app\controllers;


class SignUp extends Controller{

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('signUp/index');
    }

    function newUser() {
        $this->view->render('signUp/new');
    }

    function existing() {
        $this->view->render('signUp/existing');
    }

}