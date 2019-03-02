<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "controller/connection.php";

$view = new Template();
$menu = new Template();
$menu->properties['active'] = 3;

$content_tr = "";
$view->properties['header'] = "ข้อมูลส่วนตัว";
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');
$view->title = "LPAC IT System Dashboard";

$view->properties['display_name'] = $_SESSION['display_name'];
$view->properties['username'] = $_SESSION['user'];
$view->properties['user_id'] = $_SESSION['user_id'];

$view->properties['success'] = "";
$view->properties['error'] = "";

if (isset($_POST['success'])){
    $view->properties['success'] = $_POST['success'];
    unset($_POST['success']);
}
if (isset($_POST['error'])){
    $view->properties['error'] = $_POST['error'];
    unset($_POST['error']);
}

$view->properties['content_tr'] = $content_tr;
echo $view->render('admin_profile.html');

?>