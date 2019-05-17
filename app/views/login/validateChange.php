<?php
$errors = [];
$classes = [];

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