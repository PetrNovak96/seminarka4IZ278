<?php


namespace app\controllers;


class Events extends EntityController {

    function __construct() {
        parent::__construct();
    }

    function defaultRender() {
        $this->view->render('events/index');
    }

    function detail($parameters) {
        $id = $parameters[0];
        $this->view->ID = $id;
        $this->view->render('events/detail');
    }

    function create() {
        $this->view->render('events/form');
    }
}