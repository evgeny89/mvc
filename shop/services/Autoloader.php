<?php


namespace services;


class Autoloader extends \services\Loader
{
    function render($array = [])
    {
        // TODO: Implement render() method.
        return $this->template->render($array);
    }
}