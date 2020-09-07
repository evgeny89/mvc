<?php


namespace controllers;


use services\Autoloader;

class AdminController extends \services\FrontController
{
    protected function action_index()
    {
        $tmpl = [
            'page' => 'admin',
            'res' => [
                'user' => $this->model->getUser()
            ]
        ];

        $tmpl = $this->model->checkModer($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_orders()
    {
        $tmpl = [
            'page' => 'admin/orders',
            'res' => [
                'orders' => $this->model->getAllOrders(),
                'status' => $this->model->getAllStatus()
            ]
        ];

        $tmpl = $this->model->checkModer($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_users()
    {
        $tmpl = [
            'page' => 'admin/users',
            'res' => [
                'users' => $this->model->getAllUsers(),
                'role' => $this->model->getAllRole()
            ]
        ];

        $tmpl = $this->model->checkAdmin($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_user()
    {
        $tmpl = [
            'page' => 'admin/user',
            'res' => [
                'user' => $this->model->getUser((int)$this->props),
            ]
        ];

        $tmpl = $this->model->checkAdmin($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_product()
    {
        $tmpl = [
            'page' => 'admin/product',
            'res' => [
                'categories' => $this->model->getCategoriesList(),
                'brands' => $this->model->getBrandList()
            ]
        ];

        $tmpl = $this->model->checkAdmin($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_setUserData()
    {
        if($this->model->setUserData()) {
            return $this->action_users();
        } else {
            return $this->action_error(['content' => 'ошибка редактирования данных пользователя, поля login и name не должны быть пустыми']);
        }
    }

    protected function action_changeStatus()
    {
        if($this->model->changeStatusOrder()) {
            return $this->action_orders();
        } else {
            return $this->action_error(['content' => 'неизвестная ошибка изменения статуса заказа']);
        }
    }

    protected function action_changeRole()
    {
        if($this->model->changeUserRole()) {
            return $this->action_users();
        } else {
            return $this->action_error(['content' => 'неизвестная ошибка изменения роли пользователя']);
        }
    }

    protected function action_addProduct()
    {
        if($this->model->addProduct()) {
            return $this->action_product();
        } else {
            return $this->action_error(['content' => 'ошибка добавления нового товара']);
        }
    }
}