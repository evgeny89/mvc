<?php

// задание 1
abstract class Product
{

    public static $generalTotal;
    public $name;
    public $price;
    public $type;

    public function __construct($name, $price, $type)
    {
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    abstract public function sellProduct();

    abstract protected function addTotal();

}

class ItemProduct extends Product
{

    public static $totalSum = 0;

    public function sellProduct()
    {
        self::$totalSum += $this->price;
        $this->addTotal();
    }

    protected function addTotal()
    {
        parent::$generalTotal += $this->price;
    }
}

class DigitalProduct extends Product
{

    public static $totalSum = 0;

    public function __construct($name, $price, $type)
    {
        parent::__construct($name, $price / 2, $type);
    }

    public function sellProduct()
    {
        self::$totalSum += $this->price;
        $this->addTotal();
    }

    protected function addTotal()
    {
        parent::$generalTotal += $this->price;
    }
}

class BulkProduct extends Product
{
    public function __construct($name, $price, $type, $weight)
    {
        parent::__construct($name, round($price * $weight, 2), $type);
    }

    public static $totalSum = 0;

    public function sellProduct()
    {
        self::$totalSum += $this->price;
        $this->addTotal();
    }

    protected function addTotal()
    {
        parent::$generalTotal += $this->price;
    }
}

$item = [
    'name' => 'книга',
    'price' => 890,
    'type' => 'Большая книга CSS'
];
$groats = [
    'name' => 'горох',
    'price' => 129.99,
    'type' => 'крупы',
    'weight' => 1.3
];

$objItem = new ItemProduct($item['name'], $item['price'], $item['type']);
$objDigital = new DigitalProduct($item['name'], $item['price'], $item['type']);
$objBulk = new BulkProduct($groats['name'], $groats['price'], $groats['type'], $groats['weight']);

$objItem->sellProduct();
$objBulk->sellProduct();
$objBulk->sellProduct();
$objDigital->sellProduct();

var_dump(Product::$generalTotal);
var_dump($objDigital::$totalSum);
var_dump($objItem::$totalSum);
var_dump($objBulk::$totalSum);

// задание 2
trait SingletonTrait {
    private static $instance;

    final public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    final private function __construct() {
    }

    final private function __clone() {
    }

    final private function __wakeup() {
    }
}

class Singleton {

    use SingletonTrait;
    public static $counter = 0;
    public static $dateCreate;

    public static function getCounter()
    {
        return self::$counter;
    }

    public static function getDateCreate()
    {
        return self::$dateCreate;
    }

    public static function setCounter()
    {
        self::$counter += 1;
    }

    public static function setDateCreate()
    {
        self::$dateCreate = date('d.m.Y H:i:s');
    }
}

$a = Singleton::getInstance();
sleep(3);
$b = Singleton::getInstance();

$a::setDateCreate();

echo $b::getDateCreate();


