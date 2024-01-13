<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");
session_destroy();
header("Location: index.php");
checkAuthorization();
include("includes/header.php");
include("includes/footer.php") 
?>