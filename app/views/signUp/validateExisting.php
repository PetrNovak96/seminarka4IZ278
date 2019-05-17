<?php

$classes = array();
$errors = array();

$classes['email'] = '';
if (empty($_POST['email'])) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte emailovou adresu.';
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte validní emailovou adresu.';
} elseif($usersModel->existsUser($_POST['email'])) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Uživatel s tímto emailem už existuje.';
} elseif (!$usersModel->existsEmployee($_POST['email'])){
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Pracovník s tímto emailem neexistuje.';
}

$classes['password1'] = '';
if (empty($_POST['password1'])) {
    $classes['password1'] = 'is-invalid';
    $errors['password1'][] = 'Zadejte heslo.';
}

$classes['password2'] = '';
if (empty($_POST['password2'])) {
    $classes['password2'] = 'is-invalid';
    $errors['password2'][] = 'Zadejte potvrzovací heslo.';
}

if (!empty($_POST['password1']) &&
    !empty($_POST['password2']) &&
    $_POST['password1'] != $_POST['password2']) {
    $classes['password2'] = 'is-invalid';
    $errors['password2'][] = 'Zadaná hesla se musí shodovat.';
}