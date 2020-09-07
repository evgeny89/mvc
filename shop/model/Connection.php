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
            self::$link->setAttribute(self::$link::ATTR_ERRMODE, self::$link::ERRMODE_EXCEPTION);
        }
    }

    public function checkAuth($array) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        $res = $smtp->rowCount();
        return $res === 1 ? $array : [
            'page' => 'error',
            'res' => [
                'content' => 'Для просмотра необходимо авторизоваться'
            ]
        ];
    }

    public function checkAdmin($array) {
        $sql = "SELECT * FROM users WHERE id = :id and role > 2 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        $res = $smtp->rowCount();
        return $res === 1 ? $array :  [
            'page' => 'error',
            'res' => [
                'content' => 'Ошибка 404, Страница не найдена'
            ]
        ];
    }

    public function checkModer($array) {
        $sql = "SELECT * FROM users WHERE id = :id and role > 1 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        $res = $smtp->rowCount();
        return $res === 1 ? $array :  [
            'page' => 'error',
            'res' => [
                'content' => 'Ошибка 404, Страница не найдена'
            ]
        ];
    }

    public function checkRoot($array) {
        $sql = "SELECT * FROM users WHERE id = :id and role > 3 LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        $res = $smtp->rowCount();
        return $res === 1 ? $array :  [
            'page' => 'error',
            'res' => [
                'content' => 'Ошибка 404, Страница не найдена'
            ]
        ];
    }

    public function getAccess() {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $smtp = self::$link->prepare($sql);
        $smtp->bindParam(':id', $_SESSION['user'], self::$link::PARAM_INT);
        $smtp->execute();
        return $smtp->rowCount();
    }

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