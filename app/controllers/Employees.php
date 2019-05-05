<?php


namespace app\controllers;


class Employees extends EntityController {

    function __construct() {
        parent::__construct();

    }

    function defaultRender() {
        $this->view->render('employees/index');
    }

    function detail($parameters) {
        // TODO: Implement detail() method.
    }
}