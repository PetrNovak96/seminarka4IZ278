<?php


namespace app\model;

use \PDO;

abstract class Model {

    protected static $pdo;

    public function __construct(){
        if (!self::$pdo instanceof PDO){
            self::$pdo = new PDO(
                DB_CONNECTION,
                DB_USERNAME,
                DB_PASSWORD);
        }
    }
}