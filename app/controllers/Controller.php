<?php


namespace app\controllers;


abstract class Controller {

    protected $view;

    function __construct() {
        $this->view = new \app\views\View();
    }

    abstract function defaultRender();

}