<?php

namespace Classes;

use Exception;
use Classes\Interfaces\View;
use Twig_Environment;

class Viewer implements View
{
    private $view;

    public function __construct(Twig_Environment $twig)
    {
        $this->view = $twig;
    }

    public function render($template, array $param)
    {
        $tmpl = $this->getTemplate($template);
        return $tmpl->render($param);
    }

    private function getTemplate($name)
    {
        if(!file_exists("../templates/{$name}.tmpl")) {
            throw new Exception('шаблон не найден');
        }
        return $this->view->loadTemplate($name . '.tmpl');
    }

}