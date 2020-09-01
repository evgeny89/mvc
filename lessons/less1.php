<?php

// задания 1, 2, 3, 4.
class Product {
    private $article;
    protected $price;
    private $name;
    private $description;

    public function __construct($name, $price, $article, $description) {
        $this->name = $name;
        $this->price = $price;
        $this->article = $article;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

class Notebooks extends Product {
    public $specifications;
    public $imageUrl;
    public $model;

    public function __construct($product)
    {
        parent::__construct($product['name'], $product['price'], $product['article'], $product['description']);
        $this->specifications = $product['specifications'];
        $this->imageUrl = $product['imageUrl'];
        $this->model = $product['model'];
    }

    /**
     * @param string
     * @return mixed
     */
    public function getSpecificationsItem($name)
    {
        return $this->specifications[$name];
    }

    /**
     * @return array
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

}

class Dress extends Product {
    public $fabric;
    public $size;
    public $color;

    public function __construct($product)
    {
        parent::__construct($product['name'], $product['price'], $product['article'], $product['description']);
        $this->color = $product['color'];
        $this->size = $product['size'];
        $this->fabric = $product['fabric'];
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getFabric()
    {
        return $this->fabric;
    }

    /**
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }
}

$acer = [
    'name' => 'Acer Aspire',
    'model' => 'V3-772G',
    'price' => 42990,
    'article' => '747a4G75Makk',
    'description' => 'Семейство устройств V3-772G относится к средней ценовой категории и предназначено для широкого спектра задач. Неплохая дискретная видеокарта и вплоть до 32 Гб оперативной памяти позволят использовать такой ноутбук даже в качестве замены настольному компьютеру.',
    'imageUrl'  => 'https://c.dns-shop.ru/thumb/st4/fit/800/650/fe71261744d33a210f43ac84e14de185/34cc416d4bc28d6108de12bcd78fe42114e8f923f1383c9a6fbb079cd9ee827d.jpg',
    'specifications' => [
        'Операционная система' => 'Windows 8',
        'Экран' => [
            'Тип' => 'TN+film',
            'Разрешение' => '1920x1080',
            'Плотность пикселей' => '127.3 PPI',
            'Покрытие' => 'матовое'
        ],
        'Процессор' => [
            'Линейка' => 'Intel Core i7',
            'Модель' => 'Core i7 4702MQ',
            'Количество ядер' => 4,
            'Частота' => '2.2 ГГц',
            'Кэш L3' => '6 Мб'
        ],
        'GPU' => [
            'Тип' => 'дискретный и встроенный',
            'Производитель' => 'nVidia',
            'Модель' => 'GeForce GTX 760M',
            'Видеопамять' => [
                'Объем' => '2 Гб',
                'Тип' => 'GDDR5'
            ]
        ],
        'Оперативная память' => [
            'Тип' => 'DDR3',
            'Частота' => '1600 МГц',
            'Размер' => '8 ГБ'
        ]
    ]
];

$dress = [
    'name' => 'Froggi',
    'prise' => 2599,
    'article' => 'MP002XW10U74',
    'description' => 'Отличное платье, приятное к телу, легкое, красивое.',
    'color' => 'green',
    'size' => 42,
    'fabric' => 'Вискоза - 80%, Полиэстер - 20%'
];

$acerObj = new Notebooks($acer);
$dressObj = new Dress($dress);

/*var_dump($acerObj->getSpecificationsItem('Экран'));
echo '<br>';
var_dump($dressObj);*/


// задание 5 и 6
class A {
    public static $x;

    public function foo() {
        static $x = 0;
        echo ++$x . '<br>';
    }

    public static function showX()
    {
        echo self::$x++ . '<br>';
    }
}
$a1 = new A();
$a2 = new A();
$a1->foo();
$a2->foo();
$a1->foo();
$a2->foo();

class B extends A {
}
$a3 = new A();
$b1 = new B();
$a3->foo();
$b1->foo();
$a3->foo();
$b1->foo();

$a3->showX();
$a3->showX();
$a3->showX();
$a3->showX();

$b1->showX();
$b1->showX();

A::showX();
A::showX();
B::showX();

// немного усложнил код, чтобы сравнить статическую переменную метода и свойство класса... теперь вобще интересно...
// получается статическое свойство независимо от класса и его экземпляров, то статическая переменная независима только от экземпляров...

// задание 7
$a4 = new A;
$b2 = new B;
$a4->foo();
$b2->foo();
$a4->foo();
$b2->foo();

// если класс не имеет коструктора (т.е. не имеет аргументов или иначе входных параметров), то создавать экземпляры класса можно без указания круглых скобок. от этовго поведение программы не меняется.
