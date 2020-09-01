<?php

namespace model;

class CatalogModel extends \model\Connection
{

    protected static $num = 20;

    public function select($page)
    {
        $start = $page * self::$num;
        $stmt = self::$link->prepare("SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description, products.id FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id LIMIT :start, :num");
        $stmt->bindParam(':num', self::$num, self::$link::PARAM_INT);
        $stmt->bindParam(':start', $start, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectOne($id) {
        $stmt = self::$link->prepare("SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id WHERE products.id = :id");
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function allPage()
    {
        $smtp = self::$link->prepare("SELECT * FROM products");
        $smtp->execute();
        return $smtp->rowCount() / self::$num;
    }
}