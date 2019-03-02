<?php

include "config/site.php";
include "session.php";
include "config/database.php";
include "controller/connection.php";

function slug($str)
{
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9ก-๙เแ]/i', '-', $str);
  $str = preg_replace('/-+/', '-', $str);
  $str = preg_replace('/-$|^-/', '', $str);
	return $str ?: '-';
}

$title = $_POST['title'];
$tag = $_POST['tag'];
$body = $_POST['body'];

$content_url = slug($title);

$sql = "INSERT INTO contents
        (title, tag, body, content_url, creation)
        VALUES ('$title', '$tag', '$body', '$content_url', NOW())";

$result = $conn->query($sql);
$conn->close();
header( "location: " . SITE_HOST . "/dashboard.php");
exit(0);
// if ($result === TRUE){

// }


?>