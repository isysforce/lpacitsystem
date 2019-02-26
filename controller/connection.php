<?php

// include "config/database.php";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($conn,"utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>