<?php
include_once '../connection.php';

$markerId = $_POST['id'];

    // Delete the user marker from the database
    $sql = "DELETE FROM use_markers WHERE id = $markerId";

    if ($con->query($sql) === TRUE) {
        echo 'User marker deleted successfully';
    } else {
        echo 'Error deleting user marker: ' . $con->error;
    }

    $con->close();

?>