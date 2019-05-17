<?php


namespace app\controllers;

class Employees extends EntityController {

    function __construct($name) {
        $deletedMsg = 'Úspěšně byla provedena výpověď.';
        $deleteErrorMsg = 'Nepodařilo se provést výpověď.';
        $updatedMsg = 'Údaje pracovníka byly úspěšně upraveny.';
        $createdMsg = 'Byl vytvořen nový záznam pracovníka.';

        parent::__construct(
            $name,
            $deletedMsg,
            $deleteErrorMsg,
            $updatedMsg,
            $createdMsg
        );
    }
}