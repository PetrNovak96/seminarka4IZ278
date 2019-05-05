<?php

namespace app\controllers;

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('index/index');
    }
}