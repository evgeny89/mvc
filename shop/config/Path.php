<?php


namespace config;


class Path
{
    /**
     * путь до корневой папки
     * @return string - path
     */
    public static function getRoot() {
        return dirname(dirname(__FILE__));
    }

    /**
     * путь указывающий экшн и метод
     * @return string - например /catalog/index
     */
    public static function getPath() {
        preg_match('/(\w+\/\w+)/', $_GET['page'], $path);
        return '/' . $path[1];
    }

    /**
     * возвращает имя контроллера, инстанс которого необходимо создать
     * @return string - например user или catalog
     */
    public static function getController() {
        preg_match('/(\w+)\/\w+/', $_GET['page'], $path);
        return $path[1];
    }
}