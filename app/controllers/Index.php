<?php

namespace app\controllers;

class Index extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->render('index/index');
    }
    function ahoj($parameters = false) {
    }

}