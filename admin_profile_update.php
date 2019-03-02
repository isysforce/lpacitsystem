<?php
include "config/site.php";
include "session.php";
include "view/template.php";
include "config/database.php";
include "controller/connection.php";

$username = $_SESSION['user'];

// echo $username;
if (isset($_POST['display_name']) && isset($_POST['new_username'])){
    $display_name = $_POST['display_name'];
    $new_username = $_POST['new_username'];
    $uid = $_SESSION["user_id"];

    unset($_POST['display_name']);
    unset($_POST['new_username']);

    if (empty($display_name) || empty($new_username)){
        $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 1";
    }
    else if (strlen($display_name) < 3 || strlen($new_username) < 3){
        $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 11";
    }
    else
    {
        $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 2";
        $sql = "UPDATE admin_users SET username='$new_username', display_name='$display_name' WHERE id='$uid'";

        if ($conn->query($sql) === TRUE){
            $_POST['error'] = "";
            $_POST['success'] = "บันทึกข้อมูลสำเร็จ";
            $_SESSION["user"] = $new_username;
            $_SESSION["display_name"] = $display_name;
        } else {
            $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 32";
        }
        // $sql = "SELECT * FROM admin_users
        //         WHERE username='$username'";
        // $result = $conn->query($sql);
        
        // if ($result){
        //     $row = $result->fetch_assoc();
        //     if (!empty($row)){
        //         $sql = "UPDATE admin_users SET username='$new_username', display_name='$display_name' WHERE id='$uid'";

        //         if ($conn->query($sql) === TRUE){
        //             $_POST['error'] = "";
        //             $_POST['success'] = "บันทึกข้อมูลสำเร็จ";
        //             $_SESSION["user"] = $new_username;
        //             $_SESSION["display_name"] = $display_name;
        //         } else {
        //             $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 32";
        //         }
        //     } else {
        //         $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 31";
        //     }
        // } else {
        //     $_POST['error'] = "ไม่สามารถบันทึกข้อมูลได้ code 3";
        // }
        $conn->close();
    }
}

?>
<form id="myForm" action="<?=SITE_HOST?>/admin_profile.php" method="post">
<?php
    foreach ($_POST as $a => $b) {
        echo '<input type="hidden" name="'.htmlentities($a).'" value="'.htmlentities($b).'">';
    }
?>
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>