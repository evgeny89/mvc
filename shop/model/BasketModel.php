<?php


namespace model;


class BasketModel extends Connection
{
    public function getItems()
    {
        $sql = "SELECT products.id, products.name, products.price, products.description, baskets.count FROM baskets LEFT JOIN products ON baskets.product_id = products.id WHERE user_id = :user and status = 0";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function changeCount($id, $change)
    {
        if ($this->getAccess() !== 1) {
            return false;
        }
        $sql = "SELECT count FROM baskets WHERE status = 0 and user_id = :user and product_id = :id LIMIT 1";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() !== 1) {
            return false;
        }

        $count = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'] + $change;

        if ($count < 1) {
            $sql = "DELETE FROM baskets WHERE status = 0 and user_id = :user and product_id = :id";
        } else {
            $sql = "UPDATE baskets SET count = :num WHERE status = 0 and user_id = :user and product_id = :id";
        }
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);

        if (!$count < 1) {
            $stmt->bindParam(':num', $count, self::$link::PARAM_INT);
        }
        return $stmt->execute();
    }

    public function createOrder()
    {
        $update = "UPDATE baskets SET status = 1, order_id = :id WHERE status = 0 and user_id = :user";
        $create = "INSERT INTO orders (summ) VALUES ((SELECT SUM(products.price * baskets.count) FROM baskets LEFT JOIN products ON baskets.product_id = products.id WHERE baskets.user_id = :user AND baskets.status = 0))";

        try {
            self::$link->beginTransaction();

            $stmt = self::$link->prepare($create);
            $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
            $stmt->execute();
            $id = self::$link->lastInsertId();

            $stmt = self::$link->prepare($update);
            $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
            $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
            $stmt->execute();

            self::$link->commit();
            return true;
        } catch (\Exception $e) {
            self::$link->rollBack();
            return false;
        }
    }

    public function getUserOrders()
    {
        $sql = "SELECT orders.*, SUM(baskets.count) as count FROM orders LEFT JOIN baskets on orders.id = baskets.order_id WHERE user_id = :user AND baskets.status > 0 GROUP BY baskets.order_id ORDER BY baskets.order_id DESC";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserOrder($id)
    {
        $sql = "SELECT products.id, products.name, products.price, products.description, baskets.count, state.name as status FROM baskets LEFT JOIN products ON baskets.product_id = products.id LEFT JOIN state ON baskets.status = state.id WHERE user_id = :user and order_id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $sum = 0;
        $count = 0;

        foreach ($order as $item) {
            $count += $item['count'];
            $sum += $item['count'] * $item['price'];
        }

        return [
            'page' => 'order',
            'res' => [
                'order' => $order,
                'totalSum' => $sum,
                'totalCount' => $count,
                'status' => $order[0]['status']
            ]
        ];
    }
}