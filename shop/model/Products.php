<?php

namespace model;

class Products extends \model\Connection
{
    public static $productOnPage;

    public function select($num)
    {
        // TODO: Implement select() method.
        $stmt = self::$link->prepare("SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id LIMIT :num");
        $stmt->bindParam(':num', $num, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count()
    {
        $smtp = self::$link->prepare("SELECT * FROM products");
        $smtp->execute();
        return $smtp->rowCount();
    }


    // ниже методы для генерации товаров)
    public function select_brand()
    {
        $smtp = self::$link->prepare("SELECT * FROM brands");
        $smtp->execute();
        return $smtp->fetchAll();
    }

    public function select_category()
    {
        $smtp = self::$link->prepare("SELECT * FROM categories");
        $smtp->execute();
        return $smtp->fetchAll();
    }

    public function add($name, $price, $description, $brand, $category) {
        $stmt = self::$link->prepare("INSERT INTO products (category_id, brand_id, name, price, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $category, self::$link::PARAM_INT);
        $stmt->bindParam(2, $brand, self::$link::PARAM_INT);
        $stmt->bindParam(3, $name);
        $stmt->bindParam(4, $price, self::$link::PARAM_INT);
        $stmt->bindParam(5, $description);
        $stmt->execute();
    }
}