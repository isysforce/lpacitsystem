<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "controller/connection.php";

$view = new Template();
$menu = new Template();
$menu->properties['active'] = 5;

$content_tr = "";
$view->properties['header'] = "ผู้ดูแลระบบทั้งหมด";
$view->properties['dashboard_menu'] = $menu->render('dashboard_menu.html');
$view->title = "LPAC IT System Dashboard";

$sql = "SELECT * FROM admin_users";
$result = $conn->query($sql);
$conn->close();
while($row = $result->fetch_assoc()){
    $user_id = $row['id'];
    $username = $row['username'];
    $display = $row['display_name'];

    $content_tr .= '
    <tr>
        <td>'.$user_id.'</td>
        <td>'.$username.'</td>
        <td>'.$display.'</td>
        <td class="right aligned">
            <div class="ui right floated small labeled icon buttons">
                <a class="ui orange button" href="'.SITE_HOST.'/user_edit.php?uid='.$user_id.'"><i class="edit icon"></i>แก้ไข</a>
                <a class="ui red button" href="'.SITE_HOST.'/user_delete.php?uid='.$user_id.'"><i class="trash icon"></i>ลบ</a>
            </div>
        </td>
    </tr>
    ';
}

$view->properties['content_tr'] = $content_tr;
echo $view->render('admin_users.html');


?>