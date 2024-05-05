
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

    if (isset($_GET['edit_location'])) {
        $location_id = $_GET['edit_location'];

        // Fetch the details of the selected marker
        $select_query = "SELECT * FROM barangay WHERE id = $location_id";
        $result = mysqli_query($con, $select_query);

        if ($row = mysqli_fetch_assoc($result)) {
            $location_name = $row['barangay_name'];
            $location_latitude = $row['latitude'];
            $location_longitude = $row['longitude'];

            // Display the form for editing
            ?>
            <form action="update_location.php" method="post">
                <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
                <label for="location">Location Name:</label>
                <input type="text" id="location" name="location_name" value="<?php echo $location_name; ?>" required>
                <br>
                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude" value="<?php echo $location_latitude; ?>">
                <br>
                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude" value="<?php echo $location_longitude; ?>">
                <br>
                <input type="submit" value="Update">
            </form>
            <?php
            exit();
        }
    }
    ?>
`