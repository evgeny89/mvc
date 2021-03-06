<?php

namespace model;

class CatalogModel extends \model\Connection
{

    /**
     * @var int - количество товаров на страницу
     */
    protected static $num = 20;

    /**
     * лайт версия пагинации, получаем текущую страницу и возвращаем self::$num товаров начиная с необходимого
     * @param $page - номер страницы
     * @return array - массив ассоциативных массивов товаров
     */
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

    /**
     * получание информации о товаре
     * @param $id - id товара
     * @return array - ассоциативный массив информации о товаре
     */
    public function selectOne($id)
    {
        $sql = "SELECT CONCAT(products.name, ' / ', brands.name) as name, categories.name as category, products.price, products.description, products.id FROM products LEFT JOIN brands on products.brand_id = brands.id LEFT JOIN categories on products.category_id = categories.id WHERE products.id = :id";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * добавляет товар в корзину
     * @param $id - id товара
     * @return bool - успешность операции
     */
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
            if(!$this->checkItem($id)) {
                return false;
            }
            $sql = "INSERT INTO baskets (user_id, product_id) VALUES (:user, :id)";
        }

        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':user', $_SESSION['user']);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * получает список коментариев
     * @param $id - id товара
     * @return array - массив ассоциативных массивов коментариев
     */
    public function getComments($id)
    {
        $sql = "SELECT comments.date, comments.description as text, users.name FROM comments LEFT JOIN users ON comments.athor_id = users.id WHERE comments.product_id = :id ORDER BY comments.id DESC";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * добавление коментария
     * @return bool - успешность операции
     */
    public function addComment()
    {
        $sql = "INSERT INTO comments (athor_id, product_id, description) VALUES (:author, :product, :text)";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':author', $_SESSION['user'], self::$link::PARAM_INT);
        $stmt->bindParam(':product', $_POST['product_id'], self::$link::PARAM_INT);
        $stmt->bindParam(':text', $_POST['text'], self::$link::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * проверка существования товара
     * @param $id - id товара
     * @return bool - успешность операции
     */
    public function checkItem($id) {
        $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = self::$link->prepare($sql);
        $stmt->bindParam(':id', $id, self::$link::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * вернет общее количество страниц в каталоге
     * @return float|int - кол-во страниц
     */
    public function allPage()
    {
        $smtp = self::$link->prepare("SELECT * FROM products");
        $smtp->execute();
        return $smtp->rowCount() / self::$num;
    }
}