<?php

namespace services;

use config\Path;

class Router
{
    private $controller, $action, $props;

    /**
     * Router constructor.
     */
    public function __construct() {
        Session::start();
        $this->props = !empty($_GET['page']) ? explode('/', $_GET['page'], 3) : [];
        $this->controller = ucfirst(array_shift($this->props)) ?? 'Catalog';
        $this->action = array_shift($this->props) ?? 'index';
    }

    /**
     * проверяет, есть ли указанный в адресной строке контроллер
     * @return string - путь до класса контроллера
     */
    public function getController() {
        return file_exists( Path::getRoot() . '/controllers/'. $this->controller . 'Controller.php') ? '\controllers\\' . $this->controller . 'Controller' : '\controllers\CatalogController';
    }

    /**
     * проверяет существует ли такая модель на основании имени контроллера
     * @return string - путь до класса модели
     */
    public function getModel() {
        return file_exists( Path::getRoot() . '/model/'. $this->controller . 'Model.php') ? '\model\\' . $this->controller . 'Model' : '\model\CatalogModel';
    }

    /**
     * возвроащает экшн из адресной строки (проверка происходит уже в инстансе контроллера, во FrontController->beforeAction())
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * возвращает props из адресной строки, либо пустую строку
     * @return string
     */
    public function getProps() {
        return implode($this->props) ?? '';
    }
}