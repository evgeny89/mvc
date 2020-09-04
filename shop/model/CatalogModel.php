<?php

namespace model;

class CatalogModel extends \model\Connection
{

    protected static $num = 20;

    public function select($page)
    {
        $start = $page * self::$num;
        $sql = "SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description, products.id FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id LIMIT :start, :num";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':num', self::$num, self::$link::PARAM_INT);
        $stmt->bindParam(':start', $start, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectOne($id)
    {
        $sql = "SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description, products.id FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id WHERE products.id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function addItemInBasket($id)
    {
        $sql = "SELECT * FROM baskets WHERE status = 0 and user_id = :user and product_id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user']);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() === 1) {
            $sql = "UPDATE baskets SET count = count + 1 WHERE status = 0 and user_id = :user and product_id = :id";
        } else {
            if($this->checkItem($id) !== 1) {
                return false;
            }
            $sql = "INSERT INTO baskets (user_id, product_id) VALUES (:user, :id)";
        }

        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user']);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        return $stmt->execute();
    }

    public function checkItem($id) {
        $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function allPage()
    {
        $smtp = self::$link->prepare("SELECT * FROM products");
        $smtp->execute();
        return $smtp->rowCount() / self::$num;
    }
}