<?php

namespace services;

use config\Path;

abstract class Loader
{
    protected $twig;
    protected $loader;
    protected $template;

    public function __construct($template) {
        \Twig_Autoloader::register();
        $this->loader = new \Twig_Loader_Filesystem(Path::getRoot() . '/views/templates');
        $this->twig = new \Twig_Environment($this->loader);
        $this->template = $this->twig->loadTemplate($template);
    }

    abstract function render($array);
}