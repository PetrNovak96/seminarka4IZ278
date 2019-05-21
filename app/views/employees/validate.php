<?php

use app\Token;

$errors = array();
$classes = array();

$classes['NAME'] = '';
if (empty($_POST['NAME'])) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Zadejte křestní jméno.';
} elseif (mb_strlen($_POST['NAME']) > 50) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Křestní jméno nesmí obsahovat více než 50 znaků.';
}

$classes['SURNAME'] = '';
if (empty($_POST['SURNAME'])) {
    $classes['SURNAME'] = 'is-invalid';
    $errors['SURNAME'][] = 'Zadejte příjmení.';
} elseif (mb_strlen($_POST['SURNAME']) > 50) {
    $classes['SURNAME'] = 'is-invalid';
    $errors['SURNAME'][] = 'Příjmení nesmí obsahovat více než 50 znaků.';
}

$classes['EMAIL'] = '';
if (empty($_POST['EMAIL'])) {
    $classes['EMAIL'] = 'is-invalid';
    $errors['EMAIL'][] = 'Zadejte email.';
} elseif (!filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL)) {
    $classes['EMAIL'] = 'is-invalid';
    $errors['EMAIL'][] = 'Zadejte validní emailovou adresu.';
}

$classes['BIRTH'] = '';
if (empty($_POST['BIRTH'])) {
    $classes['BIRTH'] = 'is-invalid';
    $errors['BIRTH'][] = 'Zadejte datum narození.';
} elseif (!validateDate($_POST['BIRTH'], true)) {
    $classes['BIRTH'] = 'is-invalid';
    $errors['BIRTH'][] = 'Zadejte validní datum narození.';
}

$classes['ENTERED'] = '';
if (!empty($_POST['ENTERED']) && !validateDate($_POST['ENTERED'])) {
    $classes['ENTERED'] = 'is-invalid';
    $errors['ENTERED'][] = 'Zadejte validní datum nástupu.';
}

$classes['departments'] = '';
if (!empty($_POST['departments'])) {
    foreach ($_POST['departments'] as $department) {
        if (!$departmentsModel->exists($department)) {
            $classes['departments'] = 'is-invalid';
            $errors['departments'][] = 'Oddělení s ID '.$department.' neexistuje.';
        }
    }
}

if (empty($_POST['token'])) {
    $errors['TOKEN'][] = 'Není token...';
} elseif(!Token::check($_POST['token'])) {
    $errors['TOKEN'][] = 'Token nesedí...';
}