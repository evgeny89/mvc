<?php


namespace controllers;


use services\Autoloader;
use services\FrontController;
use services\Session;

class UserController extends FrontController
{
    protected function action_index()
    {
        $tmpl = ['page' => 'user'];
        $tmpl = $this->model->checkAuth($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res'] ?? []);
    }

    protected function action_auth()
    {
        $res = $this->model->authUser();
        $page = new Autoloader($res['page']);
        return $page->render($res['res']);
    }

    protected function action_reg()
    {
        $res = $this->model->regUser();
        $page = new Autoloader($res['page']);
        return $page->render($res['res']);
    }

    protected function action_logout()
    {
        Session::reset('user');
        header('Location: /');
        return '';
    }
}