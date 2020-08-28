<?php


namespace services;


class Session
{
    public static function start() {
        session_start();
    }

    public static function set($value) {
        $_SESSION['user'] = $value;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function reset($name) {
        unset($_SESSION[$name]);
    }

    public static function stop() {
        session_destroy();
    }
}