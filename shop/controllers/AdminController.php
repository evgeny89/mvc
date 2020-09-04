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
            'content' => 'hello world'
          ]
        ];

        $tmpl = $this->model->checkAdmin($tmpl);
        $page = new Autoloader($tmpl['page']);
        return $page->render($tmpl['res']);
    }
}