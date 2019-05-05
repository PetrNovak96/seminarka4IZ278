<?php


namespace app\controllers;


abstract class EntityController extends Controller {

    function __construct() {
        parent::__construct();
    }

    abstract function detail($parameters);
}