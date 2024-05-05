<?php

include_once 'connection.php';
include_once 'header.php';

if (isset($_SESSION['success_message'])) {
    echo '<script>alert("' . $_SESSION['success_message'] . '");</script>';
    
    unset($_SESSION['success_message']);
}

?>


    <div class="welcome-container">
        <h1 class="welcome-message">WELCOME TO DEEPWELL WATER SOURCE MAPPING SYSTEM IN ZAMBOANGA CITY</h1>
        <p>The application will then provide information about areas in Zamboanga City prone to deep well water sources. <br>
            This aims to make mapping and accessing information about deep well water sources in Zamboanga City simple and
            accessible.
        </p>
    </div>

    <div id="map"></div>

    <div id="admin-login-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('admin-login-modal')">&times;</span>
            <div class="login-container">
                <h2 class="login-title">Login</h2>
                <form id="admin-form" method="POST">
                    <input type="text" placeholder="Username" name="username" id="username" autocomplete="on">
                    <input type="password" placeholder="Password" name="password" id="password" autocomplete="on">
                    <button type="submit" name="user_login" class="submit">Login</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    include_once 'login.php';
    ?>

    <div id="user-signup-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('user-signup-modal')">&times;</span>
            <div class="login-container">
                <h2 class="login-title">Create Account</h2>
                <form id="user-signup-form" method="POST">
                    <input type="text" placeholder="Enter your username" name="username" id="signup-username" autocomplete="on">
                    <input type="password" placeholder="Password" name="password" id="signup-password" autocomplete="on">
                    <input type="password" placeholder="Confirm Password" name="conf_password" autocomplete="on">
                    <input type="text" placeholder="Location:ex.Recodo, Zamboanga City" name="location" id="location" autocomplete="on">
                    <input type="email" placeholder="Email Address" name="email" id="email">
                    <input type="text" placeholder="Contact No." name="contact_no" id="contact_no">
                    <button type="sumit" name="user_register" class="submit">Sign up</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    include_once 'register.php';
    ?>

    <script>
      var map = L.map('map').setView([6.9214, 122.0790], 13); // Coordinates for Zamboanga City
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        function openModal(modalID) {
            var modal = document.getElementById(modalID);
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }
        
    </script>
</body>

</html>
