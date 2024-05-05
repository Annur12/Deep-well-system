<?php include_once 'header.php';
      include_once 'sidebar.php';
      include_once '../connection.php';

if(isset($_POST['submit'])) {
    $barangay_name = $_POST['barangay'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    $select_query = "SELECT * FROM barangay WHERE barangay_name = '$barangay_name'";
    $result_select = mysqli_query($con, $select_query);
    $number = mysqli_num_rows($result_select);

    if($number > 0) {
        echo "<script>alert('This barangay is inserted already!')</script>";
    } else {

        $insert_query = "INSERT INTO barangay (barangay_name, longitude, latitude)
        VALUES ('$barangay_name', '$longitude', '$latitude')";
        $result = mysqli_query($con, $insert_query);
        if($result) {
            echo "<script>alert('Inserted Successfully!')</script>";
        }
    }
}
?>

<div class="content">
        <h2>Add Barangay</h2>

        <!-- Form for adding Barangay, Longitude, and Latitude -->
        <form action="#" method="post">
            <label for="barangay">Barangay:</label>
            <input type="text" id="barangay" name="barangay" required>

            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" required>

            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" required>

            <button class="barangay-button" name="submit" type="submit">Save</button>
        </form>
    </div>

<?php include_once 'footer.php'?>