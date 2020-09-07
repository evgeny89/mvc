<?php


namespace controllers;


use services\Autoloader;
use services\FrontController;
use services\Session;

class UserController extends FrontController
{
    /**
     * личный кабинет пользователя
     * @return string - сгенерированная страница
     */
    protected function action_index()
    {
        $tmpl = [
            'page' => 'user',
            'res' => [
                'user' => $this->model->getUser()
            ]
        ];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница авторизации пользователя
     * @return string - сгенерированная страница
     */
    protected function action_auth()
    {
        $res = $this->model->authUser();
        $page = new Autoloader($res['page']);
        return $page->render($res['res']);
    }

    /**
     * страница регистрации пользователя
     * @return string - сгенерированная страница
     */
    protected function action_reg()
    {
        $res = $this->model->regUser();
        $page = new Autoloader($res['page']);
        return $page->render($res['res']);
    }

    /**
     * метод завершения сессии пользователя на сайте
     * @return bool
     */
    protected function action_logout()
    {
        Session::reset('user');
        header('Location: /');
        return true;
    }
}