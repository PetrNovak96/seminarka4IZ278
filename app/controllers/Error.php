<?php


namespace app\controllers;


class Error extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function error404() {
        $this->view->msg = 'Page does not exists';
        $this->view->render('error/index');
    }
}