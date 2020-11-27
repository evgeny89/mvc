<?php


namespace services;


class Autoloader extends \services\Loader
{
    /**
     * отдаем в Twig массив для рендера страницы
     * @param $array - массив данных для страницы
     * @return string - готовая страница
     */
    public function render($array)
    {
        // TODO: Implement render() method.
        return $this->template->render($array ?? ['content' => 'Ошибка 404, страница не найдена']);
    }
}