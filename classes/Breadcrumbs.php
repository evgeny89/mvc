<?php

namespace Classes;

class Breadcrumbs
{
    private $link;
    private $uri;

    public function __construct($link) {
        $this->link = $link;
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function getCrumbs() {
        $links = $this->getLinks();
        return $this->buildLinks($links);
    }

    private function getUriArray() {
        return explode('/', preg_replace('/\?(.+)$/', '', $this->uri));
    }

    private function getLinks() {
       $sql = "SELECT name, path FROM breadcrumbs WHERE path IN (" . implode( ', ', $this->addSlash()) . ")";
       return $this->link->select_all($sql);
    }

    private function addSlash() {
        $arr = $this->getUriArray();
        foreach ($arr as &$value) {
            $value = "'{$value}'";
        }
        unset($value);
        return $arr;
    }

    private function buildLinks($arr) {
        $str = '';
        foreach ($arr as &$value) {
            $str .= "/{$value['path']}";
            $value['path'] = $str;
        }
        unset($value);
        array_unshift($arr, [
            "name"=> "Главная",
            "path"=> "/"
        ]);
        return $arr;
    }
}