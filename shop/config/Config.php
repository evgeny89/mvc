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
            'name' => 'Корзина',
            'path' => '/basket/index',
            'parent' => 2,
            'access' => 2
        ],
        [
            'id' => 6,
            'name' => 'Выход',
            'path' => '/user/logout',
            'parent' => 0,
            'access' => 2
        ],
        [
            'id' => 7,
            'name' => 'Админка',
            'path' => '/admin/index',
            'parent' => 0,
            'access' => 3
        ],
        [
            'id' => 8,
            'name' => 'Мои заказы',
            'path' => '/basket/orders',
            'parent' => 2,
            'access' => 2
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
        preg_match('/\w+\/\w+/', $_GET['page'], $path);
        $key = array_search('/' . $path[0], array_column(self::$menu, 'path'));
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) use ($key) {
                return $item['access'] < 2 && $item['parent'] === self::$menu[$key]['id'];
            });
        } else {
            return array_filter(self::$menu, function ($item) use ($key) {
                return $item['access'] !== 1 && $item['access'] <= $_SESSION['admin'] + 2 && $item['parent'] === self::$menu[$key]['id'];
            });
        }
    }
}