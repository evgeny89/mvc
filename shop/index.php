<?php

spl_autoload_register(function ($class_name) {
    if (preg_match('~Twig_~', $class_name)) {
        $class_name = 'services/' . str_replace('_', '/', $class_name);
    }
        require_once $class_name . '.php';
});

$rout = new \services\Router();

$controller = $rout->getController();
$index = new $controller(
    $rout->getAction(),
    $rout->getModel(),
    $rout->getProps()
);

$index->getAction();

