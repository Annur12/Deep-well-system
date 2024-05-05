<script>

function search() {
    var searchInput = document.getElementById('searchInput').value;

    alert('Search for: ' + searchInput);
}
    
var map = L.map('map').setView([6.9108, 122.0736], 13);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a> contributors'
        }).addTo(map);

        var markers = L.layerGroup().addTo(map);
        map.zoomControl.remove();
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        loadMarkers();

        map.on('click', function (e) {

            checkIfWithinAdminMarkers(e.latlng.lat, e.latlng.lng, function (isWithinAdminMarkers, adminMarkerDescription) {
    if (isWithinAdminMarkers) {
        if (adminMarkerDescription) {
            alert('This location is ' + adminMarkerDescription + '. Please find another location');
        } else {
            alert('This location is restricted. Please find another location.');
        }
        return;
    }

            var latitude = e.latlng.lat;
            var longitude = e.latlng.lng;

            var apiKey = '24XwBccraDoTiTbhcUYnToMs2yAj9v57';
            var tomtomReverseGeocodingURL = `https://api.tomtom.com/search/2/reverseGeocode/${latitude},${longitude}.json?key=${apiKey}`;

            $.ajax({
                type: 'GET',
                url: tomtomReverseGeocodingURL,
                success: function (response) {
                   
                    var username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>";

                    var locationName = extractLocationName(response);
                   
                    var popupContent = '<div id="popup-form">' +
                    '<label for="nameInput">Applicant Name:</label>' +
                    '<input type="text" id="nameInput" value="' + username + '" readonly><br>' +
                    '<label for="contactInput">Contact No.:</label>' +
                    '<input type="text" id="contactInput" placeholder="Enter your contact no." required><br>' +
                    '<label for="locationInput">Location:</label>' +
                    '<input type="text" id="locationInput" required value="' + locationName + '"><br>' +
                    '<label for="typeInput">Type:</label>' +
                    '<select id="typeInput">' +
                    '<option value="commercial">Commercial</option>' +
                    '<option value="residential">Residential</option>' +
                    '</select><br>' +
                    '<button onclick="saveMarker(' + e.latlng.lat + ', ' + e.latlng.lng + ')">Save</button>' +
                    '</div>';
        
                    var popup = L.popup()
                    .setLatLng(e.latlng)
                    .setContent(popupContent)
                    .openOn(map);
                },
            
                error: function (xhr, status, error) {
                    console.error('Error fetching TomTom reverse geocoding:', error);
                }
            });
        });
        });

        function extractLocationName(response) {
            try {
                if (response.addresses && response.addresses.length > 0) {
                    var firstResult = response.addresses[0];
                    var locationName = '';
        
                    if (firstResult.address) {
                        
                        if (firstResult.address.streetNumber) {
                            locationName += firstResult.address.streetNumber + ' ';
                        }
        
                        if (firstResult.address.route) {
                            locationName += firstResult.address.route + ', ';
                        }
                        if (firstResult.address.municipalitySubdivision) {
                            locationName += firstResult.address.municipalitySubdivision + ', ';
                        }
                        if (firstResult.address.municipality) {
                            locationName += firstResult.address.municipality + ', ';
                        }
        
                        locationName = locationName.replace(/,\s*$/, '');
        
                        return locationName || 'Unknown Location';
                    }
                }
        
                return 'Unknown Location';
            } catch (error) {
                console.error('Error extracting location name:', error);
                return 'Unknown Location';
            }
        }
        


        function checkIfWithinAdminMarkers(lat, lng, callback) {
    var adminMarkersURL = 'get_restricted_markers.php';

    $.ajax({
        type: 'GET',
        url: adminMarkersURL,
        success: function (response) {
    try {
        var adminMarkersData = JSON.parse(response);
        console.log('Admin Markers Data:', adminMarkersData);

        var isWithinRadius = adminMarkersData.some(function (marker) {
            var distance = map.distance([lat, lng], [marker.latitude, marker.longitude]);
            return distance <= marker.radius;
        });

        console.log('Is Within Radius:', isWithinRadius);

        var adminMarkerDescription = '';
        if (isWithinRadius) {
            var matchingMarker = adminMarkersData.find(function (marker) {
                var distance = map.distance([lat, lng], [marker.latitude, marker.longitude]);
                return distance <= marker.radius;
            });

            if (matchingMarker) {
                adminMarkerDescription = matchingMarker.description || '';
            }
        }

        console.log('Admin Marker Description:', adminMarkerDescription);

        callback(isWithinRadius, adminMarkerDescription);
    } catch (error) {
        console.error('Error fetching and parsing admin markers:', error);
        callback(false, ''); // Handle the error by providing an empty admin description
    }
},


        error: function (xhr, status, error) {
            console.error('Error fetching admin markers:', error);
            callback(false, ''); // Handle the error by providing an empty admin description
        }
    });
}

function saveMarker(latitude, longitude) {
    var name = document.getElementById('nameInput').value;
    var contact_no = document.getElementById('contactInput').value;
    var location = document.getElementById('locationInput').value;
    var markerType = document.getElementById('typeInput').value;

    // Validate contact number
    if (isNaN(contact_no) || contact_no === '') {
        alert('Please enter a valid contact number.');
        return;
    }

    // Additional parameters
    var depth = Math.floor(Math.random() * (40 - 20 + 1)) + 20; // Random depth between 20 and 40 meters
    var water_quality = "Good"; // Constant water quality

    $.ajax({
        type: 'POST',
        url: 'store_marker.php',
        data: {
            name: name,
            contact_no: contact_no,
            location: location,
            markerType: markerType,
            latitude: latitude,
            longitude: longitude,
            depth: depth,
            water_quality: water_quality
        },
        success: function (response) {
            console.log(response);

            // Close the popup
            map.closePopup();

            // Load the markers (including the newly saved one)
            loadMarkers();
        },
        error: function (xhr, status, error) {
            console.error('Error saving marker:', error);
        }
    });
}

        var greenIcon = new L.Icon({
        iconUrl: 'images/marker-icon-2x-green.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    function loadMarkers() {
        
        markers.clearLayers();
    
        $.ajax({
            type: 'GET',
            url: 'get_markers.php', 
            success: function (response) {
                try {
                    var markerData;
    
                    if (Array.isArray(response)) {
                        markerData = response;
                    } else {
                        
                        markerData = JSON.parse(response);
    
                        if (!Array.isArray(markerData)) {
                            throw new Error('Parsed data is not an array.');
                        }
                    }
    
                    console.log('Markers from server:', markerData);
    
                    markerData.forEach(function (data) {
                        var markerColor = data.status === 'Approved' ? 'red' : 'green';
    
                        var currentDate = new Date();
                        var dateString = currentDate.toLocaleDateString();
    
                      var popupContent = '<div class="marker-popup">' +
            '<b>Applicant Name:</b> <span>' + (data.name) + '</span>' +
            '<br><b>Contact No:</b> <span>' + (data.contact_no) + '</span>' +
            '<br><b>Location:</b> <span> ' + (data.location) + '</span>' +
            '<br><b>Type:</b> <span> ' + (data.markerType) + '</span>' +
            '<br><b>Depth:</b> <span> ' + (data.depth) + ' meters</span>' + // New line for depth
            '<br><b>Water Quality:</b> <span> ' + (data.water_quality) + '</span>' + // New line for water quality
            '<br><b>Status:</b> <span class="status">' + (data.status) + '</span>' +
            '<br><b>Date:</b> <span>' + dateString + '</span>' +
            '</div>';
                        var markerIcon = new L.Icon({
                            iconUrl: 'images/marker-icon-2x-' + markerColor + '.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41],
                        });
    
                        var marker = L.marker([data.latitude, data.longitude], { icon: markerIcon }).addTo(markers);
                        marker.bindPopup(popupContent, { className: 'custom-popup' });
                    });
                } catch (error) {
                    console.error('Error:', error);
                    console.log('Actual response:', response);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching markers:', error);
                console.log('Actual response:', xhr.responseText);
            
            },
        });
    }

        function clearForm() {
            document.getElementById('nameInput').value = '';
            document.getElementById('contactInput').value = '';
            document.getElementById('locationInput').value = '';
            document.getElementById('typeInput').value = '';
        }

        // user.js
        var marker;
    // Function to update the map when a barangay is clicked
    function goToBarangay(latitude, longitude) {
    
        map.setView([latitude, longitude], 16);

        var marker = L.marker([latitude, longitude]).addTo(map);

        marker.bindPopup(barangayName).openPopup();

        setTimeout(function () {
        map.removeLayer(marker);
    }, 1000);
    }

    // AJAX call to fetch barangay data
    $.ajax({
        url: 'fetch_barangay.php', // Update the path based on your file structure
        method: 'GET',
        success: function (data) {
            var barangayList = JSON.parse(data);

            // Event listener for barangay clicks
            $('.options li').on('click', function () {
                var latitude = parseFloat($(this).data('latitude'));
                var longitude = parseFloat($(this).data('longitude'));
                var barangayName = $(this).text();

                goToBarangay(latitude, longitude, barangayName);
            });
        },
        error: function (error) {
            console.error('Error fetching barangay data: ' + error);
        }
    });


</script>