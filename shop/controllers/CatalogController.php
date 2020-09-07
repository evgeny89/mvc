<?php


namespace controllers;

use services\Autoloader;
use services\FrontController;

class CatalogController extends FrontController
{
    /**
     * метод возвращает главную страницу сайта
     * @param $pageNum - номер страницы
     * @return string - сгенерированная страница
     */
    protected function action_index($pageNum = 0)
    {
        $page = new Autoloader('index');
        return $page->render([
            'products' => $this->model->select($pageNum),
            'page' => $pageNum,
            'max' => $this->model->allPage()
        ]);
    }

    /**
     * метод возвращает страницу товара
     * @param null $id - можно передать явно или через props (берется из адресной строки), явно указывается при добавлении коментария
     * @return string - сгенерированная страница товара
     */
    protected function action_single($id = null)
    {
        $tmpl = [
            'page' => 'single',
            'res' => [
                'item' => $this->model->selectOne($id ?? (int)$this->props),
                'comments' => $this->model->getComments($id ?? (int)$this->props),
                'back' => $_SERVER['HTTP_REFERER']
            ]
        ];
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    /**
     * метод добавление товара в корзину
     * @return string - возвращает либо страницу товара, либо ошибку
     */
    protected function action_add()
    {
        if($this->model->addItemInBasket((int)$this->props)) {
            return $this->action_single();
        } else {
            return $this->action_error('неизвестная ошибка добавления товара');
        }
    }

    /**
     * метод добавления сомментария
     * @return string - возвращает либо страницу товара, либо ошибку
     */
    protected function action_addComment()
    {
        if($this->model->addComment()) {
            return $this->action_single((int)$_POST['product_id']);
        } else {
            return $this->action_error('ошибка добавления отзыва');
        }
    }
}