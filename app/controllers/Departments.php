<?php


namespace app\controllers;


class Departments extends EntityController {

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('departments/index');
    }

    function detail($parameters) {
        // TODO: Implement detail() method.
    }
}