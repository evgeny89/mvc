<?php


namespace services;


use config\Config;

class FontController
{
    private $action;
    protected $props, $model;

    public function __construct($action, $model, $props = '')
    {
        $this->action = $action;
        $this->props = $props;
        $this->model = new $model();
    }

    public function getAction()
    {
        if (!$this->beforeAction()) {
            return $this->getLayout($this->action_error(['content' => 'Ошибка 403, Доступ закрыт']));
        }

        if (method_exists($this, 'action_' . $this->action)) {
            $method = 'action_' . $this->action;
            return $this->getLayout($this->$method((int)$this->props));
        } else {
            return $this->getLayout($this->action_error());
        }
    }

    protected function beforeAction()
    {
        return true;
    }

    protected function afterAction()
    {
        return true;
    }

    protected function getLayout($method)
    {
        $page = new Autoloader('layouts/index');
        return $page->render([
            'title' => $this->action,
            'content' => $method,
            'menu' => Config::menu()
        ]);
    }

    protected function action_error($props = ['content' => 'Ошибка 404, Страница не найдена'], $page = '404')
    {
        $page = new Autoloader($page);
        return $page->render($props);
    }
}