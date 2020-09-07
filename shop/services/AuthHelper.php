<?php


namespace services;


class AuthHelper
{
    /**
     * @var - объект подключения к бд
     */
    static $link;

    /**
     * AuthHelper constructor.
     * @param $link - объект подключения к бд из \model\Connection
     */
    public function __construct($link)
    {
        self::$link = $link;
    }

    /**
     * проверка авторизации пользователя
     * если все ок - вернет bool, иначе страницу ошибки
     * @return bool|string
     */
    public function checkAuth()
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        return $smtp->rowCount() === 1 ? true : 'Ошибка 403, необходима авторизация';
    }

    /**
     * проверка роли модератора
     * @return bool
     */
    public function checkModer()
    {
        $sql = "SELECT * FROM users WHERE id = :id and role > 1 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        return (bool)$smtp->rowCount();
    }

    /**
     * проверка роли администратора
     * @return bool
     */
    public function checkAdmin()
    {
        $sql = "SELECT * FROM users WHERE id = :id and role > 2 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        return (bool)$smtp->rowCount();
    }

    /**
     * проверка роли Root
     * @return bool
     */
    public function checkRoot()
    {
        $sql = "SELECT * FROM users WHERE id = :id and role > 3 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        return (bool)$smtp->rowCount();
    }
}