<?php


namespace controllers;

use services\Autoloader;
use services\FrontController;

class CatalogController extends FrontController
{
    protected function action_index($pageNum)
    {
        $page = new Autoloader('index');
        return $page->render([
            'products' => $this->model->select($pageNum),
            'page' => $pageNum,
            'max' => $this->model->allPage()
        ]);
    }

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
        $tmpl = $this->model->checkAuth($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }

    protected function action_add()
    {
        if($this->model->addItemInBasket((int)$this->props)) {
            return $this->action_single();
        } else {
            return $this->action_error(['content' => 'неизвестная ошибка добавления товара']);
        }
    }

    protected function action_addComment()
    {
        if($this->model->addComment()) {
            return $this->action_single((int)$_POST['product_id']);
        } else {
            return $this->action_error(['content' => 'ошибка добавления отзыва']);
        }
    }
}