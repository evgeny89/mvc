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

    private static $menu = [
        [
            'id' => 1,
            'name' => 'Главная',
            'path' => '/',
            'parent' => 0,
            'access' => 0
        ],
        [
            'id' => 2,
            'name' => 'Личный кабинет',
            'path' => '/user/index',
            'parent' => 0,
            'access' => 2
        ],
        [
            'id' => 3,
            'name' => 'Войти',
            'path' => '/user/auth',
            'parent' => 0,
            'access' => 1
        ],
        [
            'id' => 4,
            'name' => 'Регистрация',
            'path' => '/user/reg',
            'parent' => 3,
            'access' => 1
        ],
        [
            'id' => 5,
            'name' => 'Выход',
            'path' => '/user/logout',
            'parent' => 0,
            'access' => 2
        ],
        [
            'id' => 6,
            'name' => 'Корзина',
            'path' => '/user/basket',
            'parent' => 2,
            'access' => 2
        ],
    ];

    public static function db()
    {
        return self::$db;
    }

    public static function menu()
    {
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] !== 2;
            });
        } else {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] !== 1;
            });
        }
    }
}