<?php


namespace model;


class UserModel extends Connection
{
    public function selectUser($login) {
            $stmt = self::$link->prepare("SELECT * FROM users  WHERE login = :login");
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            return $stmt->fetch();
    }
}