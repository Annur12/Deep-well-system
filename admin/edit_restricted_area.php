<html>
<head>
    <title>Edit Restricted Area</title>
    <!-- Add your CSS includes here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        form {
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Edit Restricted Area</h2>
    <?php
    include_once '../connection.php';

    if (isset($_GET['edit_restricted_area'])) {
        $marker_id = $_GET['edit_restricted_area'];

        // Fetch the details of the selected marker
        $select_query = "SELECT * FROM restricted_area WHERE id = $marker_id";
        $result = mysqli_query($con, $select_query);

        if ($row = mysqli_fetch_assoc($result)) {
            $marker_location = $row['location_name'];
            $marker_radius = $row['radius'];
            $marker_latitude = $row['latitude'];
            $marker_longitude = $row['longitude'];

            // Display the form for editing
            ?>
            <form action="update_restricted_area.php" method="post">
                <input type="hidden" name="marker_id" value="<?php echo $marker_id; ?>">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo $marker_location; ?>" required>
                <br>
                <label for="radius">Radius (in meter/s):</label>
                <input type="number" id="radius" name="radius" value="<?php echo $marker_radius; ?>" required>
                <br>
                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude" value="<?php echo $marker_latitude; ?>" readonly>
                <br>
                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude" value="<?php echo $marker_longitude; ?>" readonly>
                <br>
                <input type="submit" value="Update">
            </form>
            <?php
            exit();
        }
    }
    ?>
</body>
</html>
`