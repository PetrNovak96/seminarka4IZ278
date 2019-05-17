<?php
$errors = array();
$classes = array();

//NAME  req max 50
$classes['NAME'] = '';
if (empty($_POST['NAME'])) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Zadejte název akce.';
} elseif (mb_strlen($_POST['NAME']) > 50) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Název akce nesmí obsahovat více než 50 znaků.';
}
//PLACE max 100
$classes['PLACE'] = '';
if (!empty($_POST['PLACE']) && mb_strlen($_POST['PLACE']) > 100) {
    $classes['PLACE'] = 'is-invalid';
    $errors['PLACE'][] = 'Místo konání nesmí obsahovat více než 100 znaků.';
}
//DESCRIPTION req max 300
$classes['DESCRIPTION'] = '';
if (empty($_POST['DESCRIPTION'])) {
    $classes['DESCRIPTION'] = 'is-invalid';
    $errors['DESCRIPTION'][] = 'Zadejte popis akce.';
} elseif (mb_strlen($_POST['DESCRIPTION']) > 300) {
    $classes['DESCRIPTION'] = 'is-invalid';
    $errors['DESCRIPTION'][] = 'Popis akce nesmí obsahovat více než 300 znaků.';
}
//BE_DATE
$classes['BE_DATE'] = '';
$classes['BE_TIME'] = '';
if (!empty($_POST['BE_DATE'])) {
    if(!validateDate($_POST['BE_DATE'], false)) {
        $classes['BE_DATE'] = 'is-invalid';
        $errors['BE'][] = 'Zadejte validní datum začátku akce.';
    }
    if (empty($_POST['EN_DATE'])) {
        $classes['EN_DATE'] = 'is-invalid';
        $errors['EN'][] = 'Zadejte datum konce akce.';
    }
    if (empty($_POST['BE_TIME'])) {
        $classes['BE_TIME'] = 'is-invalid';
        $errors['BE'][] = 'Zadejte čas začátku akce.';
    } elseif (!validateTime($_POST['BE_TIME'])) {
        $classes['BE_TIME'] = 'is-invalid';
        $errors['BE'][] = 'Zadejte validní čas začátku akce.';
    }
}
//BE_TIME

//EN_DATE
$classes['EN_DATE'] = '';
$classes['EN_TIME'] = '';
if (!empty($_POST['EN_DATE'])) {
    if(!validateDate($_POST['EN_DATE'], false)) {
        $classes['EN_DATE'] = 'is-invalid';
        $errors['EN'][] = 'Zadejte validní datum konce akce.';
    }
    if (empty($_POST['BE_DATE'])) {
        $classes['BE_DATE'] = 'is-invalid';
        $errors['BE'][] = 'Zadejte datum začátku akce.';
    }
    if (empty($_POST['EN_TIME'])) {
        $classes['EN_TIME'] = 'is-invalid';
        $errors['EN'][] = 'Zadejte čas konce akce.';
    } elseif (!validateTime($_POST['EN_TIME'])) {
        $classes['EN_TIME'] = 'is-invalid';
        $errors['EN'][] = 'Zadejte validní čas konce akce.';
    }
}
//EN_TIME

if(!empty($_POST['BE_DATE']) && !empty($_POST['EN_DATE'])
    && $_POST['BE_DATE'].' '.$_POST['BE_TIME'] > $_POST['EN_DATE'].' '.$_POST['EN_TIME']) {
    $classes['BE_DATE'] = 'is-invalid';
    $classes['EN_DATE'] = 'is-invalid';
    $errors['BE'][] = 'Datum začátku musí předcházet datu konce akce.';
}

//department req
$classes['department'] = '';
if (empty($_POST['department'])) {
    $classes['department'] = 'is-invalid';
    $errors['department'][] = 'Zadejte oddělení organizující tuto akci.';
} elseif (!$departmentsModel->exists($_POST['department'])) {
    $classes['department'] = 'is-invalid';
    $errors['department'][] = 'Oddělení s ID '.$_POST['department'].' neexistuje.';
}

//CAPACITY req
$classes['CAPACITY'] = '';
if (empty($_POST['CAPACITY'])) {
    $classes['CAPACITY'] = 'is-invalid';
    $errors['CAPACITY'][] = 'Zadejte kapacitu akce.';
} elseif (!filter_var($_POST['CAPACITY'], FILTER_VALIDATE_INT) || $_POST['CAPACITY'] < 1) {
    $classes['CAPACITY'] = 'is-invalid';
    $errors['CAPACITY'][] = 'Kapacita akce musí být přirozené číslo.';
} else {
    $employeesModel = new \app\model\EmployeesModel();
    $participants = $employeesModel->findParticipants($this->ID);
    $count = count($participants);
    if ($_POST['CAPACITY'] < $count) {
        $classes['CAPACITY'] = 'is-invalid';
        $errors['CAPACITY'][] = 'Počet přihlášených pracovníků ('.$count.') nesmí přesáhnout kapacitu akce.';
    }
}