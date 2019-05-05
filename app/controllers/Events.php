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
        // TODO: Implement detail() method.
    }
}