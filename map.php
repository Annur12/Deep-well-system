<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deep Well Water Source System</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
</head>
<body>
  <div id="map"></div>
  <textarea id="locationDescription" placeholder="Enter location description"></textarea>
  <button onclick="markLocation()">Mark Current Location</button>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    let map;
    let marker;

    function initMap() {
      try {
        map = L.map("map").setView([6.9214, 122.0790], 13); // Set initial view to Zamboanga City

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Check if the browser supports Geolocation
        if ("geolocation" in navigator) {
          navigator.geolocation.getCurrentPosition(
            (position) => {
              const userLocation = [position.coords.latitude, position.coords.longitude];
              map.setView(userLocation, 13);

              // Add a marker at the user's current location
              marker = L.marker(userLocation).addTo(map);
              marker.bindPopup("<p>Your current location</p>").openPopup();
            },
            (error) => {
              console.error("Error getting user location:", error);
            }
          );
        } else {
          console.error("Geolocation is not supported by your browser");
        }

        map.on("click", (event) => {
          if (marker) {
            map.removeLayer(marker);
          }

          marker = L.marker(event.latlng).addTo(map);
          marker.bindPopup("<p>Click to add description</p>").openPopup();

          marker.on("click", () => {
            const description = prompt("Enter location description:");
            if (description) {
              marker.bindPopup(`<p>${description}</p><p>Wait for admin confirmation</p>`).openPopup();
              // Send the location and description to the server for confirmation
              // Example: sendLocationToServer(marker.getLatLng(), description);
            }
          });
        });
      } catch (error) {
        console.error("Error initializing map:", error);
      }
    }

    function markLocation() {
      if (!marker) {
        alert("Please mark a location on the map.");
        return;
      }

      const description = document.getElementById("locationDescription").value;

      if (description) {
        marker.bindPopup(`<p>${description}</p><p>Wait for admin confirmation</p>`).openPopup();
        // Send the location and description to the server for confirmation
        // Example: sendLocationToServer(marker.getLatLng(), description);
      } else {
        alert("Please enter a description for the location.");
      }
    }

    document.addEventListener("DOMContentLoaded", function () {
      initMap();
    });
  </script>
</body>
</html>
