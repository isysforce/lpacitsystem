<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "controller/connection.php";

$username = $_SESSION['user'];

// echo $username;
if (isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['confirm_new_pass'])){
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_new_pass = $_POST['confirm_new_pass'];
    unset($_POST['old_pass']);
    unset($_POST['new_pass']);
    unset($_POST['confirm_new_pass']);
    if (empty($old_pass) || empty($new_pass) || empty($confirm_new_pass)){
        $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 1";
    }
    else if (strlen($old_pass) < 6 || strlen($new_pass) < 6 || strlen($confirm_new_pass) < 6){
        $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 11";
    }
    else
    {
        $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 2";
        if ($new_pass === $confirm_new_pass){
            $sql = "SELECT * FROM admin_users
                    WHERE username='$username'";
            $result = $conn->query($sql);
            
            if ($result){
                $row = $result->fetch_assoc();
                if (!empty($row)){
                    if ($row['password'] === $old_pass){

                        $sql = "UPDATE admin_users SET password='$confirm_new_pass' WHERE username='$username'";

                        if ($conn->query($sql) === TRUE){
                            $_POST['error'] = "";
                            $_POST['success'] = "เปลี่ยนรหัสผ่านสำเร็จ";
                        } else {
                            $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 33";
                        }
                    } else {
                        $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 32";
                    }
                } else {
                    $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 31";
                }
            } else {
                $_POST['error'] = "ไม่สามารถเปลี่ยนรหัสผ่านได้ code 3";
            }
            $conn->close();
        }
    }
}
// header( "location: " . SITE_HOST . "/admin_password.php");
// exit(0);
?>
<form id="myForm" action="<?=SITE_HOST?>/admin_password.php" method="post">
<?php
    foreach ($_POST as $a => $b) {
        echo '<input type="hidden" name="'.htmlentities($a).'" value="'.htmlentities($b).'">';
    }
?>
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>