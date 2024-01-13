<?php

session_start();

function checkAuthorization() {
    // Check if the user is logged in and authorized
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        // User is not authorized, redirect to index page
        header("Location: index.php");
        exit();
    }
}

?>