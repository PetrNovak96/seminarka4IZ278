<?php


namespace app\controllers;


abstract class EntityController extends Controller {

    //VELKÉ TODO: refaktorovat modely (děděná metoda exists) a controllory,
    // aby se tu daly kontrolovat existence záznamů a kdyžtak hodit 404
    protected $name;
    protected $deletedMsg;
    protected $deleteErrorMsg;
    protected $updatedMsg;
    protected $createdMsg;

    function __construct($name, $deletedMsg, $deleteErrorMsg, $updatedMsg, $createdMsg) {
        parent::__construct();
        $this->name = $name;
        $this->deletedMsg = $deletedMsg;
        $this->deleteErrorMsg = $deleteErrorMsg;
        $this->updatedMsg = $updatedMsg;
        $this->createdMsg = $createdMsg;

    }

    public function detail($parameters){
        $id = @$parameters[0];
        $className = '\app\model\\'.ucfirst($this->name).'Model';
        $model = new $className();
        if ($model->exists($id)) {
            $this->view->ID = $id;
            $this->view->render($this->name.'/detail');
        } else {
            $errorController = new Error();
            $errorController->error404();
        }

    }

    public function edit($parameters) {
        $id = @$parameters[0];
        $className = '\app\model\\'.ucfirst($this->name).'Model';
        $model = new $className();
        if ($model->exists($id)) {
            $this->view->ID = $id;
            $this->view->render($this->name.'/form');
        } else {
            $errorController = new Error();
            $errorController->error404();
        }
    }

    public function edited($parameters) {
        $id = @$parameters[0];
        $className = '\app\model\\'.ucfirst($this->name).'Model';
        $model = new $className();
        if ($model->exists($id)) {
            $this->view->ID = $id;
            $this->view->info = $this->updatedMsg;
            $this->view->render($this->name.'/detail');
        } else {
            $errorController = new Error();
            $errorController->error404();
        }
    }

    public function create() {
        $this->view->render($this->name.'/form');
    }

    public function created() {
        $this->view->info = $this->createdMsg;
        $this->view->render($this->name.'/index');
    }

    public function defaultRender() {
        $this->view->render($this->name.'/index');
    }

    function delete($parameters) {
        $id = @$parameters[0];
        $className = '\app\model\\'.ucfirst($this->name).'Model';
        $model = new $className();
        $result = $model->delete($id);
        if ($result) {
            http_response_code(400);
            //$this->deleteError($id, $result);
            echo $result;
        } else {
            http_response_code(200);
        }
    }

    public function deleteError($parameters) {
        $id = $parameters[0];
        $msg = $parameters[1];
        $this->view->ID = $id;
        $this->view->error = true;
        $this->view->info = $this->deleteErrorMsg.' '.$msg;
        $this->view->render($this->name.'/detail');
    }

    public function deleted($parameters) {
        $id = $parameters[0];
        $this->view->ID = $id;
        $this->view->info = $this->deletedMsg;
        $this->view->render($this->name.'/index');
    }

}