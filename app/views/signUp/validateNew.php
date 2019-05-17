<?php
$classes = array();
$errors = array();


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

$classes['email'] = '';
if (empty($_POST['EMAIL'])) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte emailovou adresu.';
} elseif (!filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL)) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte validní emailovou adresu.';
} elseif($usersModel->existsUser($_POST['EMAIL'])) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Uživatel s tímto emailem už existuje.';
} elseif ($usersModel->existsEmployee($_POST['EMAIL'])){
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Pracovník s tímto emailem už existuje.';
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
