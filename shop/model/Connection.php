<?php


namespace model;

use config\Config;

abstract class Connection
{
    protected static $link;

    public function __construct()
    {
        if (!self::$link) {
            $db = Config::db();
            self::$link = new \PDO("mysql:host={$db['host']};dbname={$db['name']}", $db['user'], $db['password']);
            self::$link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }
}