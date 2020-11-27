<?php


namespace model;

use config\Config;
use services\AuthHelper;

abstract class Connection
{
    /**
     * @var \PDO - объект подключения в бд
     */
    protected static $link;

    /**
     * @var AuthHelper - инстанс хелпера проверки доступа
     */
    public $access;

    /**
     * создаем объект подключения к бд
     * Connection constructor.
     */
    public function __construct()
    {
        if (!self::$link) {
            $db = Config::db();
            self::$link = new \PDO("mysql:host={$db['host']};dbname={$db['name']}", $db['user'], $db['password']);
            self::$link->setAttribute(self::$link::ATTR_ERRMODE, self::$link::ERRMODE_EXCEPTION);
            $this->access = new AuthHelper(self::$link);
        }
    }

    /**
     * возвращает данные юзера
     * @param null $id - необязательный аргумент, нужен для просмотра чужих страниц пользователей (свой id берется из сессии)
     * @return array - ассоциативный массив данных пользователя
     */
    public function getUser($id = null)
    {
        $id = $id ?? $_SESSION['user'];
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}