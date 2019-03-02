<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "controller/connection.php";

$view = new Template();
$menu = new Template();
$menu->properties['active'] = 4;

$content_tr = "";
$view->properties['header'] = "เปลี่ยนรหัสผ่าน";
$view->properties['success'] = "";
$view->properties['error'] = "";
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');
$view->title = "LPAC IT System Dashboard";

if (isset($_POST['success'])){
    $view->properties['success'] = $_POST['success'];
    unset($_POST['success']);
}
if (isset($_POST['error'])){
    $view->properties['error'] = $_POST['error'];
    unset($_POST['error']);
}

$view->properties['content_tr'] = $content_tr;
echo $view->render('admin_password.html');


?>