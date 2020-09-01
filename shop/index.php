<?php

require_once 'services/Loader.php';
require_once 'services/Autoloader.php';
require_once 'services/Twig/Autoloader.php';

require_once 'config/Path.php';
require_once 'config/Config.php';

require_once 'model/Connection.php';
require_once 'model/Products.php';

use services\Autoloader;
use model\Products;

//генерирует 200 карточек)
//require_once 'controllers/generate.php';

Products::$productOnPage = 25;

$products = new Products();
$page = $_GET['page'] ?? 1;
$count = $products->count();

try {
    $index = new Autoloader('catalog.tmpl');
    echo $index->render([
        'products' => $products->select((int)$page * Products::$productOnPage),
        'next' => $count > (int)$page * Products::$productOnPage ? (int)$page + 1 : null
    ]);
} catch (Exception $e) {
    die('ERROR ' . $e->getMessage());
}
