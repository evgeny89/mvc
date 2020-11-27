<?php

namespace services;

use config\Path;

abstract class Loader
{
    protected $twig;
    protected $loader;
    protected $template;

    /**
     * Loader constructor.
     * @param $template - имя шаблона
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __construct($template) {
        \Twig_Autoloader::register();
        $this->loader = new \Twig_Loader_Filesystem(Path::getRoot() . '/views/templates');
        $this->twig = new \Twig_Environment($this->loader);
        $this->template = $this->twig->loadTemplate($template . '.tmpl');
    }

    /**
     * абстрактный метод для рендера в шаблоне данных и генерации страницы
     * @param $array - массив данных для страницы
     * @return string - страница для отображения
     */
    abstract function render($array);
}