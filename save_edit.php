<?php

include "config/site.php";
include "session.php";
include "config/database.php";
include "controller/connection.php";

$content_id = $_GET['cid'];
$title = $_POST['title'];
$tag = $_POST['tag'];
$body = $_POST['body'];

$sql = "UPDATE contents
        SET title='$title', tag='$tag', body='$body'
        WHERE id='$content_id'";

$result = $conn->query($sql);
$conn->close();
header( "location: " . SITE_HOST . "/dashboard.php");
exit(0);
// if ($result === TRUE){

// }
?>