<?php
include "config/site.php";
include "config/database.php";
include "controller/connection.php";
include "view/template.php";

session_start();
if (!isset($_SESSION['user'])){
    $view = new Template();
    $view->properties['error'] = "";
    if (isset($_POST['user']) && isset($_POST['pass'])){
        $user = $_POST['user'];
        $pass = $_POST['pass'];

        $sql = "SELECT * FROM admin_users
                WHERE (username='$user' AND password='$pass')";

        $result = $conn->query($sql);
        $conn->close();
        
        if ($result){
            
            $row = $result->fetch_assoc();
            if (!empty($row)){
                // print_r($row);
                $_SESSION["user"] = $row['username'];
                $_SESSION["display_name"] = $row['display_name'];
                $_SESSION["user_id"] = $row['id'];
                header( "location: " . SITE_HOST . "/dashboard.php");
                exit(0);
            }
        }
        $view->properties['error'] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
    
    $view->title = "เข้าสู่ระบบ";
    
    echo $view->render("login.html");
    return;
}
header( "location: " . SITE_HOST . "/dashboard.php");
exit(0);
?>