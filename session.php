<?php

// session_save_path(SITE_HOST . "/session/");
session_start();

if (!isset($_SESSION['user'])){
    header( "location: " . SITE_HOST . "/login.php");
    exit(0);
}

?>