<?php


namespace app\controllers;


class Departments extends EntityController {



    function __construct($name) {
        $deletedMsg = 'Oddělení bylo úspěšně odstraněno.';
        $deleteErrorMsg = 'Nepodařilo se odstranit oddělení.';
        $updatedMsg = 'Oddělení bylo úspěšně upraveno.';
        $createdMsg = 'Bylo úspěšně vytvořeno nové oddělění.';

        parent::__construct(
            $name,
            $deletedMsg,
            $deleteErrorMsg,
            $updatedMsg,
            $createdMsg
        );
    }
}