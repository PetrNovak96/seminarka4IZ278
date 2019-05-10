<?php


namespace app\controllers;


class Controller {

    protected $view;

    function __construct() {
        $this->view = new \app\views\View();
    }

}