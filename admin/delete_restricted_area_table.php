<?php
include_once '../connection.php';

if (isset($_GET['delete_restricted_area'])) {
    $marker_id = $_GET['delete_restricted_area'];

    // Select the record to be archived
    $selectQuery = "SELECT * FROM restricted_area WHERE id = $marker_id";
    $selectResult = mysqli_query($con, $selectQuery);

    if ($selectResult && $row = mysqli_fetch_assoc($selectResult)) {
        // Insert the selected record into the archived table
        $insertQuery = "INSERT INTO archived_restricted_area (location_name, description, radius, latitude, longitude) VALUES (
            '{$row['location_name']}', '{$row['description']}', '{$row['radius']}', '{$row['latitude']}', '{$row['longitude']}'
        )";

        $insertResult = mysqli_query($con, $insertQuery);

        if (!$insertResult) {
            die('Error archiving record: ' . mysqli_error($con));
        }
    }

    // Perform the delete operation
    $delete_restricted = "DELETE FROM restricted_area WHERE id = $marker_id";
    mysqli_query($con, $delete_restricted);

    // Redirect back to the page displaying the list
    header("Location: restricted_area_table.php");
    exit();
}
?>
