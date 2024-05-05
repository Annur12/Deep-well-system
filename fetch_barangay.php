<?php
include_once 'connection.php';

$select_barangay_names = "SELECT * FROM barangay";
$result_barangay_names = mysqli_query($con, $select_barangay_names);

$barangayList = array();
if ($result_barangay_names) {
    while ($row_barangay = mysqli_fetch_assoc($result_barangay_names)) {
        $barangayList[] = $row_barangay;
    }
} else {
    echo "Error: " . mysqli_error($con);
}

// Return JSON-encoded data
echo json_encode($barangayList);
