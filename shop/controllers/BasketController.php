<?php


namespace controllers;


use services\Autoloader;

class BasketController extends \services\FrontController
{
    /**
     * главная страница корзины
     * @return string - сгенерированная страница
     */
    protected function action_index()
    {
        $tmpl = [
            'page' => 'basket',
            'res' => [
                'items' => $this->model->getItems()
            ]
        ];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница заказов пользователя
     * @return string - сгенерированная страница
     */
    protected function action_orders()
    {
        $tmpl = [
            'page' => 'orders',
            'res' => [
                'orders' => $this->model->getUserOrders(),
            ]
        ];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * страница конкретного заказ
     * @return string - сгенерированная страница
     */
    protected function action_order()
    {
        $tmpl = $this->model->getUserOrder((int)$this->props);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * метод увеличения товара в корзине на 1
     * @return string - вернет либо обратно, либо на страницу ошибки
     */
    protected function action_changePlus()
    {
        if ($this->model->changeCount($this->props, 1)) {
            return $this->action_index();
        } else {
            return $this->action_error('Ошибка изменения количества товара');
        }
    }

    /**
     * метод уменьшения товара в корзине на 1
     * @return string - вернет либо обратно, либо на страницу ошибки
     */
    protected function action_changeMinus()
    {
        if ($this->model->changeCount($this->props, -1)) {
            return $this->action_index();
        } else {
            return $this->action_error('Ошибка изменения количества товара');
        }
    }

    /**
     * метод оформления заказа
     * @return string - вернет либо страницу заказов, либо ошибку
     */
    protected function action_createOrder()
    {
        if ($this->model->createOrder()) {
            return $this->action_orders();
        } else {
            return $this->action_error('Ошибка оформления заказа');
        }
    }
}