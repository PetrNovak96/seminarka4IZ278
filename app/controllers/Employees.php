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
        $id = $parameters[0];
        $this->view->ID = $id;
        $this->view->render('employees/detail');
    }

    function create() {
        $this->view->render('employees/form');
    }
}