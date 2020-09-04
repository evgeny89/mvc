<?php


namespace controllers;


use services\Autoloader;

class BasketController extends \services\FrontController
{
    protected function action_index()
    {
        $tmpl = [
            'page' => 'basket',
            'res' => [
                'items' => $this->model->getItems()
            ]
        ];
        $tmpl = $this->model->checkAuth($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res'] ?? []);
    }

    protected function action_orders()
    {
        $tmpl = [
            'page' => 'orders',
            'res' => [
                'orders' => $this->model->getUserOrders()
            ]
        ];
        $tmpl = $this->model->checkAuth($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res'] ?? []);
    }

    protected function action_order()
    {
        $tmpl = $this->model->getUserOrder((int)$this->props);
        $tmpl = $this->model->checkAuth($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res'] ?? []);
    }

    protected function action_changePlus()
    {
        if ($this->model->changeCount($this->props, 1)) {
            return $this->action_index();
        } else {
            return $this->action_error(['content' => 'Ошибка изменения количества товара']);
        }
    }

    protected function action_changeMinus()
    {
        if ($this->model->changeCount($this->props, -1)) {
            return $this->action_index();
        } else {
            return $this->action_error(['content' => 'Ошибка изменения количества товара']);
        }
    }

    protected function action_createOrder()
    {
        if ($this->model->createOrder()) {
            return $this->action_index();
        } else {
            return $this->action_error(['content' => 'Ошибка оформления заказа']);
        }
    }
}