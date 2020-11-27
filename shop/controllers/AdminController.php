<?php


namespace controllers;


use services\Autoloader;

class AdminController extends \services\FrontController
{
    /**
     * главная страница админки
     * @return string - сгенерированная страница
     */
    protected function action_index()
    {
        $tmpl = [
            'page' => 'admin',
            'res' => [
                'user' => $this->model->getUser()
            ]
        ];

        $tmpl = $this->model->access->checkModer() ? $tmpl : ['page' => 'error'];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница управления заказами пользователей
     * @return string - сгенерированная страница
     */
    protected function action_orders()
    {
        $tmpl = [
            'page' => 'admin/orders',
            'res' => [
                'orders' => $this->model->getAllOrders(),
                'status' => $this->model->getAllStatus()
            ]
        ];

        $tmpl = $this->model->access->checkModer() ? $tmpl : ['page' => 'error'];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница управления пользователями, назначение им прав
     * @return string - сгенерированная страница
     */
    protected function action_users()
    {
        $tmpl = [
            'page' => 'admin/users',
            'res' => [
                'users' => $this->model->getAllUsers(),
                'role' => $this->model->getAllRole()
            ]
        ];

        $tmpl = $this->model->access->checkModer() ? $tmpl : ['page' => 'error'];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница управление пользователем, изменение его данных
     * @return string - сгенерированная страница
     */
    protected function action_user()
    {
        $tmpl = [
            'page' => 'admin/user',
            'res' => [
                'user' => $this->model->getUser((int)$this->props),
            ]
        ];

        $tmpl = $this->model->access->checkModer() ? $tmpl : ['page' => 'error'];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница добавление новых товаров в каталог
     * @return string - сгенерированная страница
     */
    protected function action_product()
    {
        $tmpl = [
            'page' => 'admin/product',
            'res' => [
                'categories' => $this->model->getCategoriesList(),
                'brands' => $this->model->getBrandList()
            ]
        ];

        $tmpl = $this->model->access->checkModer() ? $tmpl : ['page' => 'error'];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * метод вызываемый роутером при изменении данных пользователя
     * @return string - сгенерированная страница
     */
    protected function action_setUserData()
    {
        if($this->model->setUserData()) {
            return $this->action_users();
        } else {
            return $this->action_error('ошибка редактирования данных пользователя, поля login и name не должны быть пустыми');
        }
    }

    /**
     * метод вызываемый роутером при изменении статуса заказа
     * @return string - сгенерированная страница
     */
    protected function action_changeStatus()
    {
        if($this->model->changeStatusOrder()) {
            return $this->action_orders();
        } else {
            return $this->action_error('неизвестная ошибка изменения статуса заказа');
        }
    }

    /**
     * метод вызываемый роутером при изменении роли пользователя
     * @return string - сгенерированная страница
     */
    protected function action_changeRole()
    {
        if($this->model->changeUserRole()) {
            return $this->action_users();
        } else {
            return $this->action_error('неизвестная ошибка изменения роли пользователя');
        }
    }

    /**
     * метод вызываемый роутером при добавлении продукта в каталог
     * @return string - сгенерированная страница
     */
    protected function action_addProduct()
    {
        if($this->model->addProduct()) {
            return $this->action_product();
        } else {
            return $this->action_error('ошибка добавления нового товара');
        }
    }
}