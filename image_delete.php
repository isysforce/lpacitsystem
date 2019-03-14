<?php

include "config/site.php";
include "session.php";

if (isset($_POST['imageName'])){
    $imagePath = "upload/" . $_POST['imageName'];
    if (file_exists($imagePath)){
        $deleteSuccess = unlink($imagePath);
        if ($deleteSuccess){
            echo "ok";
            exit();
        }
        
    }
    
}
echo "fail";
exit();
?>