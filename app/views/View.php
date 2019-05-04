<?php


namespace app\views;

class View {

    private $base;

    function __construct() {
        //$this->base = $_SERVER['DOCUMENT_ROOT'];
    }

    public function render($name) {
        require __DIR__.'/'.$name.'.php';
    }

    public function header() {
        $this->render('header');
    }

    public function footer() {
        $this->render('footer');
    }
}