<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "view/getTags.php";

$view = new Template();


include "controller/connection.php";
$sql = "SELECT *
        FROM tags";

$tags = "";

if ($result = $conn->query($sql)){
    $conn->close();
    while($row = $result->fetch_assoc()) {
        $tags .= '<option value="' . $row['id'] . '">' . $row['tag_name'] . '</option>';
    }
    
}
$view->properties['header'] = "เพิ่มเนื้อหา";
$view->properties['content_title'] = "";
$view->properties['body'] = "";
$view->properties['tags'] = $tags;
$view->properties['action_url'] = SITE_HOST . "/save_add.php";
$view->properties['submit_btn'] = "เพิ่มเนื้อหา";

$view->title = "LPAC IT System Dashboard";

$menu = new Template();

$menu->properties['active'] = 2;
$menu->properties['menu_title'] = "เพิ่มเนื้อหา";
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');

echo $view->render('add_content.html');

?>