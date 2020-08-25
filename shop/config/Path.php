<?php


namespace config;


class Path
{
    public static function getRoot() {
        return dirname(dirname(__FILE__));
    }
}