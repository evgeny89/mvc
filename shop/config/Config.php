<?php


namespace config;


class Config
{
    private static $db = [
      'host' => 'localhost',
      'user' => 'root',
      'password' => 'root',
      'name' => 'shop'
    ];

    public static function db() {
        return self::$db;
    }
}