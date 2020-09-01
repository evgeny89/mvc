<?php

namespace services;

use config\Path;

class Router
{
    private $controller, $action, $props;

    public function __construct() {
        Session::start();
        $this->props = !empty($_GET['page']) ? explode('/', $_GET['page'], 3) : [];
        $this->controller = ucfirst(array_shift($this->props)) ?? 'Catalog';
        $this->action = array_shift($this->props) ?? 'index';
    }

    public function getController() {
        return file_exists( Path::getRoot() . '/controllers/'. $this->controller . 'Controller.php') ? '\controllers\\' . $this->controller . 'Controller' : '\controllers\CatalogController';
    }

    public function getModel() {
        return file_exists( Path::getRoot() . '/model/'. $this->controller . 'Model.php') ? '\model\\' . $this->controller . 'Model' : '\model\CatalogModel';
    }

    public function getAction() {
        return $this->action;
    }

    public function getProps() {
        return implode($this->props) ?? '';
    }
}