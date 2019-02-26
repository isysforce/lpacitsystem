<?php
include "config/database.php";
include "view/template.php";

$view = new Template();
$view->title = "Content title";

echo $view->render('dashboard.html');

?>