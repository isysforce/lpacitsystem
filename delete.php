<?php

include "config/site.php";
include "session.php";
include "config/database.php";
include "controller/connection.php";

$content_id = $_GET['cid'];

$sql = "DELETE FROM contents
        WHERE id='$content_id'";

$result = $conn->query($sql);
$conn->close();
header( "location: " . SITE_HOST . "/dashboard.php");
exit(0);

?>