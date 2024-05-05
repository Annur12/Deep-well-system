<?php 
include_once 'header.php'; 
include_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or display a message
    header('Location: homepage.php');
    exit;
}
?>

<div class="wrapper">
    <div class="select-btn">
        <span>Select Barangay</span>
        <i class="uil uil-angle-down"></i>
    </div>

    <div class="content">
        <div class="search">
        <i class="uil uil-angle-search"></i>
        <input type="text" placeholder="Search">
        </div>
        <ul class="options">
        <?php 
            $select_brangay_names = "SELECT * FROM barangay";
            $result_barangay_names = mysqli_query($con, $select_brangay_names);

            if ($result_barangay_names) {
                while ($row_barangay = mysqli_fetch_assoc($result_barangay_names)) {
                    $barangay_name = $row_barangay['barangay_name'];
                    $longitude = $row_barangay['longitude'];
                    $latitude = $row_barangay['latitude'];
                    echo "<li data-latitude='$latitude' data-longitude='$longitude'>$barangay_name</li>";
                } 
            }  else {
                echo "Error: " . mysqli_error($con);
            }
            ?>
        </ul>
    </div>
</div>

    <div id="map"></div>

<?php
include_once 'footer.php';
?>

