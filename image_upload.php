<?php

include "config/site.php";
include "session.php";
// echo $_FILES["file"];
if (isset($_FILES["file"]["type"])) {
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["file"]["type"] == "image/png") ||
         ($_FILES["file"]["type"] == "image/jpg") ||
         ($_FILES["file"]["type"] == "image/jpeg")) &&
         ($_FILES["file"]["size"] < 5000000) && in_array($file_extension, $validextensions)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"];
        } else {
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists.";
            } else {
                $newFileName = time() . "." . $file_extension;
                $sourcePath = $_FILES['file']['tmp_name'];
                $targetPath = "upload/" . $newFileName;

                $moveStatus = move_uploaded_file($sourcePath, $targetPath);

                if ($moveStatus){
                    echo "ok".$newFileName;
                } else {
                    echo "fail";
                }

                
            }
        }
    } else {
        echo "invalid file type";
    }
}
exit();
?>