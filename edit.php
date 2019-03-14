<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "view/getTags.php";

$view = new Template();

$content_id = $_GET['cid'];

include "controller/connection.php";

$sql = "SELECT *
        FROM contents
        WHERE id='$content_id'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$tag_id = $row['tag'];
$view->properties['content_title'] = $row['title'];
$view->properties['body'] = $row['body'];
// $view->properties['tag_default'] = $tags;

$sql = "SELECT *
        FROM tags";

$tags = "";

if ($result = $conn->query($sql)){
    $conn->close();
    while($row = $result->fetch_assoc()) {
        if ($row['id'] === $tag_id){
            $tags .= '<option value="' . $row['id'] . '" selected>' . $row['tag_name'] . '</option>';
        } else {
            $tags .= '<option value="' . $row['id'] . '">' . $row['tag_name'] . '</option>';
        }
        
    }
    
}

$view->properties['header'] = "แก้ไขเนื้อหา";
$view->properties['action_url'] = SITE_HOST . "/save_edit.php?cid=" . $content_id;
$view->properties['tags'] = $tags;
$view->properties['submit_btn'] = "บันทึก";

$view->properties['gallery'] = getUploadedImages();

$view->title = "LPAC IT System Dashboard";

$menu = new Template();
$menu->properties['active'] = 2;
$menu->properties['menu_title'] = "แก้ไขเนื้อหา";
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');
echo $view->render('add_content.html');

?>