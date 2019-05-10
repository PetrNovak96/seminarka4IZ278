<?php


namespace app;

use app\controllers\Error;
use app\controllers\Index;

class Bootstrap {

    function __construct() {
        $urlString = rtrim(@$_GET['url'], '/');
        $url = explode('/', $urlString);
        if (!empty($url[0])) {
            $className = '\app\controllers\\'.ucfirst($url[0]);
            if (class_exists($className)) {
                $instance = new $className;
                if (isset($url[1]) && method_exists($instance, $url[1])) {
                    $parameters = array_slice($url, 2);
                    $instance->{$url[1]}($parameters);
                }
            } else {
                $error = new Error();
                $error->error404();
            }
        } else {
            $index = new Index();
        }
    }
}