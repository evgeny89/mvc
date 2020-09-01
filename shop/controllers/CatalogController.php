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

    protected function action_single()
    {
        $tmpl = [
            'page' => 'single',
            'res' => [
                'item' => $this->model->selectOne((int)$this->props),
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

}