<?php

use app\Token;

$errors = array();
$classes = array();

$classes['event'] = '';
if(empty($_POST['EVENT_ID'])) {
    $classes['event'] = 'is-invalid';
    $errors['event'] = 'Zadejte akci';
} elseif(!$eventsModel->exists($_POST['EVENT_ID'])) {
    $classes['event'] = 'is-invalid';
    $errors['event'] = 'Zadaná akce i ID '.$_POST['EVENT_ID'].' neexistuje.';
}

$classes['participants'] = '';
if(!empty($_POST['participants'])) {

    $event = $eventsModel->findEvent($_POST['EVENT_ID']);
    if ($event['CAPACITY'] < count($_POST['participants'])) {
        $classes['participants'] = 'is-invalid';
        $errors['participants'][] =
        'Počet přihlášených pracovníků nesmí přesáhnout kapacitu akce ('.$event['CAPACITY'].').';
    } else {
        foreach ($_POST['participants'] as $participant) {
            if (!$employeesModel->exists($participant)) {
                $classes['participants'] = 'is-invalid';
                $errors['participants'][] = 'Pracovník s ID '.$participant.' neexistuje.';
            }
        }
    }
}

if (empty($_POST['token'])) {
    $errors['TOKEN'][] = 'Není token...';
} elseif(!Token::check($_POST['token'])) {
    $errors['TOKEN'][] = 'Token nesedí...';
}