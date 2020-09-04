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
            'access' => 1,
            'sub-menu' => ['user']
        ],
        [
            'id' => 5,
            'name' => 'Корзина',
            'path' => '/basket/index',
            'parent' => 2,
            'access' => 2,
            'sub-menu' => ['user', 'basket']
        ],
        [
            'id' => 7,
            'name' => 'Админка',
            'path' => '/admin/index',
            'parent' => 0,
            'access' => 4,
        ],
        [
            'id' => 8,
            'name' => 'Мои заказы',
            'path' => '/basket/orders',
            'parent' => 2,
            'access' => 2,
            'sub-menu' => ['user', 'basket']
        ],
        [
            'id' => 6,
            'name' => 'Выход',
            'path' => '/user/logout',
            'parent' => 2,
            'access' => 2,
            'sub-menu' => ['user']
        ],
        [
            'id' => 9,
            'name' => 'Управление заказами',
            'path' => '/admin/orders',
            'parent' => 7,
            'access' => 4,
            'sub-menu' => ['admin']
        ],
        [
            'id' => 10,
            'name' => 'Управление пользователями',
            'path' => '/admin/users',
            'parent' => 7,
            'access' => 5,
            'sub-menu' => ['admin']
        ],
        [
            'id' => 11,
            'name' => 'Добавить товар',
            'path' => '/admin/product',
            'parent' => 7,
            'access' => 5,
            'sub-menu' => ['admin']
        ]
    ];

    public static function db()
    {
        return self::$db;
    }

    public static function menu()
    {
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] < 2 && $item['parent'] === 0;
            });
        } else {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] !== 1 && $item['access'] <= $_SESSION['admin'] + 2 && $item['parent'] === 0;
            });
        }
    }

    public static function subMenu() {
        preg_match('/(\w+)\/\w+/', $_GET['page'], $path);
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) use ($path) {
                return $item['access'] < 2 && isset($item['sub-menu']) && in_array($path[1], $item['sub-menu']);
            });
        } else {
            return array_filter(self::$menu, function ($item) use ($path) {
                return $item['access'] !== 1 && isset($item['sub-menu']) && $item['access'] <= $_SESSION['admin'] + 2 && in_array($path[1], $item['sub-menu']);
            });
        }
    }
}
