<?php


namespace model;


class AdminModel extends Connection
{
    public function getAllOrders()
    {
        $sql = "SELECT baskets.user_id, baskets.count, baskets.order_id, baskets.status as state_id, users.name as user, orders.date, orders.summ FROM baskets LEFT JOIN users on baskets.user_id = users.id LEFT JOIN orders on baskets.order_id = orders.id WHERE baskets.order_id is not null ORDER BY baskets.order_id DESC";
        $stmt = self::$link->prepare($sql);
        $stmt->execute();
        return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), array($this, 'reduce_cb'), []);
    }

    public function getAllStatus()
    {
        $sql = "SELECT * FROM state";
        $stmt = self::$link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(self::$link::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users WHERE role < :admin ORDER BY role DESC";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':admin', $_SESSION['admin'], self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(self::$link::FETCH_ASSOC);
    }

    public function getAllRole()
    {
        $sql = "SELECT * FROM role WHERE id < :user";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['admin'], self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(self::$link::FETCH_ASSOC);
    }

    public function getBrandList()
    {
        $sql = "SELECT * FROM brands";
        $stmt = self::$link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(self::$link::FETCH_ASSOC);
    }

    public function getCategoriesList()
    {
        $sql = "SELECT * FROM categories";
        $stmt = self::$link->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(self::$link::FETCH_ASSOC);
    }

    public function setUserData()
    {
        if (empty($_POST['name']) || empty($_POST['login'])) {
            return false;
        }

        $sql = empty($_POST['password']) ? "UPDATE users SET login = :login, name = :name WHERE id = :id" : "UPDATE users SET login = :login, name = :name, password = :password WHERE id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $_POST['id'], self::$link::PARAM_INT);
        $stmt->bindParam(':name', $_POST['name'], self::$link::PARAM_STR);
        $stmt->bindParam(':login', $_POST['login'], self::$link::PARAM_STR);
        if(!empty($_POST['password'])) {
            $stmt->bindParam(':password', $_POST['password'], self::$link::PARAM_STR);
        }
        return $stmt->execute();
    }

    public function changeStatusOrder()
    {
        $sql = "UPDATE baskets SET status = :status WHERE order_id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':status', $_POST['status'], self::$link::PARAM_INT);
        $stmt->bindParam(':id', $_POST['order'], self::$link::PARAM_INT);
        return $stmt->execute();
    }

    public function changeUserRole()
    {
        if($_SESSION['admin'] <= $_POST['role']) {
            return false;
        }

        $sql = "UPDATE users SET role = :role WHERE id = :user";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':role', $_POST['role'], self::$link::PARAM_INT);
        $stmt->bindParam(':user', $_POST['user'], self::$link::PARAM_INT);
        return $stmt->execute();
    }

    public function addProduct()
    {
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $price = $_POST['price'];
        $category_id = $_POST['category'];
        $brand_id = $_POST['brand'];

        if(empty($name) || empty($desc) || empty($price) || empty($category_id) || empty($brand_id)) {
            return false;
        }

        $sql = "INSERT INTO products (category_id, brand_id, name, price, description) VALUES (:category, :brand, :name, :price, :desc)";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':category', $category_id, self::$link::PARAM_INT);
        $stmt->bindParam(':brand', $brand_id, self::$link::PARAM_INT);
        $stmt->bindParam(':price', $price, self::$link::PARAM_INT);
        $stmt->bindParam(':name', $name, self::$link::PARAM_STR);
        $stmt->bindParam(':desc', $desc, self::$link::PARAM_STR);
        return $stmt->execute();
    }

    private function reduce_cb($res, $item)
    {
        if (isset($res[$item['order_id']])) {
            $res[$item['order_id']]['count'] += $item['count'];
        } else {
            $res[$item['order_id']] = $item;
            $res[$item['order_id']]['count'] = (int)$res[$item['order_id']]['count'];
            $res[$item['order_id']]['summ'] = (int)$res[$item['order_id']]['summ'];
            $res[$item['order_id']]['user_id'] = (int)$res[$item['order_id']]['user_id'];
            unset($res[$item['order_id']]['order_id']);
        }
        return $res;
    }
}