<?php


namespace app;

use app\controllers\Error;
use app\controllers\Index;

class Bootstrap {

    function __construct() {
        $urlString = rtrim(@$_GET['url'], '/');
        $url = explode('/', $urlString);
        $noSessionPages = ['login', 'signUp', 'google'];
        if (!empty($url[0])) {
            if (!in_array($url[0], $noSessionPages)) {
                $this->login_redirect();
            }
            $className = '\app\controllers\\'.ucfirst($url[0]);
            if (class_exists($className)) {
                $instance = new $className($url[0]);
                if (isset($url[1]) && method_exists($instance, $url[1])) {
                    $parameters = array_slice($url, 2);
                    $instance->{$url[1]}($parameters);
                } elseif(isset($url[1]) && is_numeric($url[1])) {
                    $instance->defaultRender($url[1]);
                } elseif (!isset($url[1])) {
                    $instance->defaultRender();
                } else {
                    $error = new Error();
                    $error->error404();
                }
            } else {
                $error = new Error();
                $error->error404();
            }
            } else {
                $this->login_redirect();
                $index = new Index();
                $index->defaultRender();
            }
    }

    function login_redirect() {
        if (empty($_SESSION['userId']) || empty($_SESSION['email'])) {
            echo '<h1>nemáš sešny</h1>';
            header('Location: /~novp19/login/');
        }
    }
}