<?php


namespace app\controllers;


use app\model\EventsModel;

class Events extends EntityController {

    private $partUpdateMsg;

    function __construct($name) {
        $deletedMsg = 'Akce byla úspěšně odstraněna.';
        $deleteErrorMsg = 'Nepodařilo se odstranit akci.';
        $updatedMsg = 'Údaje akce byly úspěšně upraveny.';
        $createdMsg = 'Byla vytvořena nová akce.';
        $this->partUpdateMsg = 'Přihlášky pro akci byly aktualizovány.';

        parent::__construct(
            $name,
            $deletedMsg,
            $deleteErrorMsg,
            $updatedMsg,
            $createdMsg
        );
    }

    public function apply($parameters) {
        $id = $parameters[0];
        $eventsModel = new EventsModel();
        if (!$eventsModel->exists($id) || $eventsModel->endedEvent($id)) {
            $errorController = new Error();
            $errorController->error404();
        } else {
            $this->view->ID = $id;
            $this->view->render('events/apply');
        }
    }

    public function updatedParticipations($parameters) {
        $id = $parameters[0];
        $this->view->ID = $id;
        $this->view->info = $this->partUpdateMsg;
        $this->view->render($this->name.'/detail');
    }

    public function report() {
        if (!empty($_POST)) {
            $employeeId = $_POST['employee'];
            $eventId = $_POST['event'];
            $eventsModel = new EventsModel();
            if (!$eventsModel->exists($eventId)) {
                http_response_code(400);
            } else {
                $event = $eventsModel->findEvent($eventId);
                if ($event['ENDING'] >= date('Y-m-d H:i')) {
                    http_response_code(400);
                } else {
                    $eventsModel->report($employeeId, $eventId);
                    http_response_code(200);
                }
            }
        }
    }
}