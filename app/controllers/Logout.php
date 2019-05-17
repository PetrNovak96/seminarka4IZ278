<?php


namespace app\controllers;


use app\CurrentUser;

class Logout extends Controller
{

    function defaultRender() {
       CurrentUser::logout();
    }
}