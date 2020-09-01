<?php


namespace controllers;


use services\Autoloader;
use services\FontController;
use services\Session;

class UserController extends FontController
{
    protected function action_index() {
        $page = new Autoloader('user');

        return $page->render();
    }

    protected function action_auth() {
        $page = new Autoloader('auth');

        if (empty($_POST['login']) || empty($_POST['password'])) {
            return $page->render();
        }

        $login = $this->model->selectUser((string) $_POST['login']);

        if(!empty($login) && password_verify($_POST['password'], $login['password'])) {
            Session::set($login['id']);
            header('Location: /');
            return '';
        } else {
            return $page->render(['login' => $_POST['login']]);
        }

    }

    protected function action_reg() {
        $page = new Autoloader('reg');
        return $page->render();
    }

    protected function action_logout() {
        Session::reset('user');
        header('Location: /');
        return '';
    }
}