<?php


namespace controllers;

use services\Autoloader;
use services\FontController;

class CatalogController extends FontController
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
        $page = new Autoloader('single');

        return $page->render([
            'item' => $this->model->selectOne((int)$this->props),
            'back' => $_SERVER['HTTP_REFERER']
        ]);
    }

}