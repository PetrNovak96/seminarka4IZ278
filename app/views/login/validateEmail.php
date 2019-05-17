<?php
$errors = [];
$classes = [];

$classes['email'] = '';
if (empty($_POST['email'])) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte emailovou adresu.';
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $classes['email'] = 'is-invalid';
    $errors['email'][] = 'Zadejte validní emailovou adresu.';
}