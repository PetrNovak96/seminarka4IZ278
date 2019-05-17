<?php


namespace app\views;

class View {

    function __construct() {}

    public function render($name) {
        require __DIR__.'/'.$name.'.php';
    }

    public function header($navbar = true) {
        $this->navbar = $navbar;
        $this->render('header');
    }

    public function footer($jumbotron = true) {
        $this->jumbotron = $jumbotron;
        $this->render('footer');
    }
}