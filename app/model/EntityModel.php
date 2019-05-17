<?php


namespace app\model;

abstract class EntityModel extends Model {


    public function __construct(){
        parent::__construct();
    }

    public abstract function delete($id);

    public abstract function exists($id);
}