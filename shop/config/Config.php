<?php


namespace config;


class Config
{
    /**
     * @var array[] - массив для коннекта к бд
     */
    private static $db = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'name' => 'shop'
    ];

    /**
     * @var array[] - массив элементов меню
     */
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
            'access' => 3,
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
            'access' => 3,
            'sub-menu' => ['admin']
        ],
        [
            'id' => 10,
            'name' => 'Управление пользователями',
            'path' => '/admin/users',
            'parent' => 7,
            'access' => 4,
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

    /**
     * данные для соединения с бд
     * @return array[] - массив коннекта
     */
    public static function db()
    {
        return self::$db;
    }

    /**
     * отфильтрованное меню под конкретного пользователя с учетом авторизации и прав доступа
     * @return array[] - массив меню
     */
    public static function menu()
    {
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] < 2 && $item['parent'] === 0;
            });
        } else {
            return array_filter(self::$menu, function ($item) {
                return $item['access'] !== 1 && $item['access'] <= $_SESSION['admin'] + 1 && $item['parent'] === 0;
            });
        }
    }

    /**
     * так же как и меню определяет дочерние пункты текущего меню и фильтрует по авторизации и доступу
     * @return array[] - массив подменю
     */
    public static function subMenu()
    {
        $path = Path::getController();
        if (empty($_SESSION['user'])) {
            return array_filter(self::$menu, function ($item) use ($path) {
                return $item['access'] < 2 && isset($item['sub-menu']) && in_array($path, $item['sub-menu']);
            });
        } else {
            return array_filter(self::$menu, function ($item) use ($path) {
                return $item['access'] !== 1 && isset($item['sub-menu']) && $item['access'] <= $_SESSION['admin'] + 1 && in_array($path, $item['sub-menu']);
            });
        }
    }

    /**
     * нужно для проверки доступа во \FrontController->beforeAction()
     * проверяет доступ по массиву menu (страницы товара нет в массиве, отдельно его проверяю)
     * @return integer - число доступа
     */
    public static function access()
    {
        if(preg_match('~\/single\/?\d*~', Path::getPath()) === 1) return 2;
        return array_reduce(self::$menu, function ($carry, $item) {
            return $item['path'] === Path::getPath() ? $carry = $item['access'] : $carry;
        }, 0);
    }
}
