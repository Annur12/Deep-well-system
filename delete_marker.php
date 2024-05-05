<?php
include_once 'connection.php';
session_name('user_session');
session_start();


if (isset($_POST['markerId'])) {
    $markerId = $_POST['markerId'];

    // Your database deletion logic
    $sql = "DELETE FROM use_markers WHERE id = '$markerId'";

    // Execute the query and handle errors
    if ($con->query($sql) === TRUE) {
        echo 'success'; // Respond with "success" upon successful deletion
    } else {
        echo 'Error deleting marker: ' . $con->error;
    }
}
?>
