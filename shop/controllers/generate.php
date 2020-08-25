<?php

require_once 'config/Path.php';
require_once 'config/Config.php';

require_once 'model/Connection.php';
require_once 'model/Products.php';

use model\Products;

$prod = new Products();

$brands = $prod->select_brand();
$category = $prod->select_category();
$name = [
    'Майка',
    'Футболка',
    'Топ',
    'Кроссовки',
    'Тапочки',
];
$description = [
  'хлопок 100%',
  'Полиэстер 85% хлопок 15%',
  'натуральная кожа 100%',
  'текстиль 100%',
];
$price = 10000;
$a = 200;

while ($a > 0) {
    $rand_brand = array_rand($brands);
    $rand_category = array_rand($category);
    $rand_name = array_rand($name);
    $rand_desc = array_rand($description);
    $rand_price = mt_rand(500, $price);
    $prod->add($name[$rand_name], $rand_price, $description[$rand_desc], $brands[$rand_brand]['id'], $category[$rand_category]['id']);
    $a--;
}