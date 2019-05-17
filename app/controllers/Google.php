<?php


namespace app\controllers;


use app\CurrentUser;
use app\GoogleAPI;
use app\model\UsersModel;

class Google extends Controller
{

    function defaultRender() {}

    function login() {
        $googleAPI = new GoogleAPI();
        $userData = $googleAPI->getUserData($_GET['code']);
        $email = $userData['email'];
        $name = $userData['givenName'];
        $surname = $userData['familyName'];
        $usersModel = new UsersModel();
        if ($usersModel->existsUser($email)) {
            //pokud už existuje uživatel s tímto emailem, hned ho přihlásíme
            $id = $usersModel->findUser($email)['ID'];
            CurrentUser::login($email, $id);
        } elseif ($usersModel->existsEmployee($email)) {
            //pokud neexistuje uživatel, ale existuje pracovník, přesměruju na
            // existing formulář s vyplněným emailem
            $temp = [
                'email' => $email,
            ];
            $this->view->formData = $temp;
            $this->view->render('signUp/existing');
        } else {
            //pokud s tímto emailem neexistuje ani uživatel ani pracovník,
            // přesměruju na new registrační formulář
            $temp = [
                'email' => $email,
                'NAME' => $name,
                'SURNAME' => $surname,
            ];
            $this->view->formData = $temp;
            $this->view->render('signUp/new');
        }
    }
}