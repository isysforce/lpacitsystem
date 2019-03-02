<?php

include "config/site.php";
session_start();
session_unset();
session_destroy();

header( "location: " . SITE_HOST);
exit(0);

?>