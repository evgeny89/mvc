<?php


namespace services;


class Session
{
    /**
     * запуск сессии
     */
    public static function start() {
        session_start();
    }

    /**
     * добавление записи в сессию
     * @param $name - ключ
     * @param $value - значение
     */
    public static function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * проверка на наличие ключа user присваиваемого при авторизации
     * @return bool
     */
    public static function check() {
        return isset($_SESSION['user']);
    }

    /**
     * удаление записи в сессии
     * @param $name - ключ для удаления
     */
    public static function reset($name) {
        unset($_SESSION[$name]);
    }

    /**
     * уничтожение сессии
     */
    public static function stop() {
        session_destroy();
    }
}