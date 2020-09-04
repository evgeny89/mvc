<?php


namespace services;


use config\Config;

class FrontController
{
    private $action;
    protected $props, $model;

    public function __construct($action, $model, $props)
    {
        $this->action = $action;
        $this->props = $props;
        $this->model = new $model();
    }

    public function getAction()
    {
        if ($this->beforeAction()) {
            $method = 'action_' . $this->action;
            echo $this->getLayout($this->$method((int)$this->props));
        } else {
            echo $this->getLayout($this->action_error());
        }

        $this->afterAction();
    }

    protected function beforeAction()
    {
        return method_exists($this, 'action_' . $this->action);
    }

    protected function afterAction()
    {
        // write history & log
        return true;
    }

    protected function getLayout($method)
    {
        $page = new Autoloader('layouts/index');
        return $page->render([
            'title' => $this->action,
            'content' => $method,
            'menu' => Config::menu(),
            'submenu' => Config::subMenu()
        ]);
    }

    protected function action_error($props = ['content' => 'Ошибка 404, Страница не найдена'])
    {
        $page = new Autoloader('error');
        return $page->render($props);
    }
}