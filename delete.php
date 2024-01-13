<?php

include("includes/database.php");
include("includes/config.php");
include("includes/functions.php");

checkAuthorization();

$id = $_GET['id'];
$tl = $_GET['tl'];
$back = $_GET['back'];
var_dump($id,$tl);
// Perform the deletion query
$sql = "DELETE FROM $tl WHERE id = $id";
$result = mysqli_query($connect, $sql);

// Check if the deletion was successful
if ($result) {
    echo "Record deleted successfully.";
    header("Location: $back.php");
} else {
    echo "Error deleting record: " . mysqli_error($connect);
}

include("includes/header.php");
include("includes/footer.php") 
?>