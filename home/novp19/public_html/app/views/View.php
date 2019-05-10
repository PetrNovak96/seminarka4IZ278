<?php


namespace app\views;

class View {

    function __construct() {
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