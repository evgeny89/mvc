<?php


namespace model;


use services\Session;

class UserModel extends Connection
{
    public function authUser()
    {

        if (empty($_POST['login']) || empty($_POST['password'])) {
            return [
                'page' => 'auth',
                'res' => [
                    'login' => $_POST['login']
                ]
            ];
        }

        $login = (string)$_POST['login'];
        $stmt = self::$link->prepare("SELECT * FROM users  WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $log = $stmt->fetch();

        if (!empty($log) && password_verify($_POST['password'], $log['password'])) {
            Session::set('user', $log['id']);
            Session::set('admin', $log['role']);
            return [
                'page' => 'user',
                'res' => [
                    'user' => $log
                ]
            ];
        } else {
            return [
                'page' => 'error',
                'res' => [
                    'content' => 'Ошибка авторизации, пара <i>login & password</i> не обнаружена'
                ]
            ];
        }
    }

    public function regUser() {
        if (empty($_POST['login']) || empty($_POST['password']) || empty($_POST['name'])) {
            return [
                'page' => 'reg',
                'res' => [
                    'login' => $_POST['login'],
                    'name' => $_POST['name'],
                    'msg' => 'Все поля обязательны для заполнения'
                ]
            ];
        }

        $login = (string)$_POST['login'];
        $stmt = self::$link->prepare("SELECT * FROM users  WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $log = $stmt->rowCount();

        if($log !== 0) {
            return [
                'page' => 'reg',
                'res' => [
                    'login' => $_POST['login'],
                    'name' => $_POST['name'],
                    'msg' => '<i>Login</i> занят'
                ]
            ];
        }

        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = self::$link->prepare("INSERT INTO users (login, password, name) VALUES (:login, :password, :name)");
        $stmt->bindParam(':login', $_POST['login']);
        $stmt->bindParam(':password', $pass);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->execute();

        return $this->authUser();
    }
}