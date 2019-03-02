<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "view/getTags.php";

$view = new Template();
$menu = new Template();
$menu->properties['active'] = 1;

include "controller/connection.php";
$sql = "SELECT *
        FROM contents";

$content_tr = "";
if ($result = $conn->query($sql)){
    $conn->close();
    while($row = $result->fetch_assoc()) {
        $content_tr_view = new Template();
        $content_tr_view->properties['content_id'] = $row["id"];
        $content_tr_view->properties['content_title'] = $row["title"];
        $content_tr_view->properties['content_tag'] = getTag($row["tag"])['tag_name'];
        $content_tr_view->properties['content_date'] = $row["creation"];
        $content_tr .= $content_tr_view->render('content_tr.html');
    }
    
}

$view->properties['content_tr'] = $content_tr;
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');
$view->title = "LPAC IT System Dashboard";
echo $view->render('dashboard.html');

?>