<?php

use Classes\Viewer;
use Classes\Connect;
use Classes\Breadcrumbs;

require_once '../lib/Twig/Autoloader.php';
require_once '../classes/interfaces/View.php';
require_once '../classes/Viwer.php';
require_once '../classes/interfaces/Connecting.php';
require_once '../classes/Connect.php';
require_once '../classes/Breadcrumbs.php';

Twig_Autoloader::register();
$link = new Connect('mvc');
$sql = "SELECT gallery.id, gallery.name, gallery.path, gallery.date_create as date, gallery.views, gallery.size, users.name as user_name FROM `gallery` LEFT JOIN `users` ON gallery.athor_id = users.id ORDER BY `views` DESC";
$link->pitures = $link->select_all($sql);

$breadcrumbs = new Breadcrumbs($link);

try {
    $loader = new Twig_Loader_Filesystem('../templates');
    $twig = new Twig_Environment($loader);
    $template = new Viewer($twig);
} catch (Exception $e) {
    die('ERROR' . $e->getMessage());
}

echo $template->render('gallery', ['pictures' => $link->pitures, 'links' => $breadcrumbs->getCrumbs()]);

