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
        $id = $parameters[0];
        $this->view->ID = $id;
        $this->view->render('departments/detail');
    }

    function create() {
        $this->view->render('departments/form');
    }
}