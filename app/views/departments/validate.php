<?php

use app\Token;

$errors = array();
$classes = array();

//NAME req max 50
$classes['NAME'] = '';
if (empty($_POST['NAME'])) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Zadejte název oddělení.';
} elseif (mb_strlen($_POST['NAME']) > 50) {
    $classes['NAME'] = 'is-invalid';
    $errors['NAME'][] = 'Název oddělení nesmí obsahovat více než 50 znaků.';
}
//ROOM req max 50
$classes['ROOM'] = '';
if (empty($_POST['ROOM'])) {
    $classes['ROOM'] = 'is-invalid';
    $errors['ROOM'][] = 'Zadejte název místnosti.';
} elseif (mb_strlen($_POST['ROOM']) > 50) {
    $classes['ROOM'] = 'is-invalid';
    $errors['ROOM'][] = 'Název místnosti nesmí obsahovat více než 50 znaků.';
}
//BUILDING req max 50
$classes['BUILDING'] = '';
if (empty($_POST['BUILDING'])) {
    $classes['BUILDING'] = 'is-invalid';
    $errors['BUILDING'][] = 'Zadejte název budovy.';
} elseif (mb_strlen($_POST['BUILDING']) > 50) {
    $classes['BUILDING'] = 'is-invalid';
    $errors['BUILDING'][] = 'Název budovy nesmí obsahovat více než 50 znaků.';
}
//HEAD_ID req
$classes['HEAD_ID'] = '';
if (empty($_POST['HEAD_ID'])) {
    $classes['HEAD_ID'] = 'is-invalid';
    $errors['HEAD_ID'][] = 'Zadejte vedoucího oddělení.';
} elseif (!$employeesModel->exists($_POST['HEAD_ID'])) {
    $classes['HEAD_ID'] = 'is-invalid';
    $errors['HEAD_ID'][] = 'Zadejte existujícího vedoucího oddělení.';
}
//DEPARTMENT_ID
$classes['DEPARTMENT_ID'] = '';
if (!empty($_POST['DEPARTMENT_ID']) &&
    !$departmentsModel->exists($_POST['DEPARTMENT_ID'])) {
    $classes['DEPARTMENT_ID'] = 'is-invalid';
    $errors['DEPARTMENT_ID'][] = 'Zadejte existujícího oddělení.';
}

if (empty($_POST['token'])) {
    $errors['TOKEN'][] = 'Není token...';
} elseif(!Token::check($_POST['token'])) {
    $errors['TOKEN'][] = 'Token nesedí...';
}
