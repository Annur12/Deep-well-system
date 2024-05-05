var map = L.map('map').setView([6.9108, 122.0736], 13);

L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a> contributors'
}).addTo(map);

map.zoomControl.remove();

L.control.zoom({ position: 'bottomright' }).addTo(map);

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    if (sidebar.style.left === '-250px' || sidebar.style.left === '') {
        sidebar.style.left = '0';
        content.style.marginLeft = '250px';
    } else {
        sidebar.style.left = '-250px';
        content.style.marginLeft = '0';
    }
}

function toggleDropdown(dropdownId) {
    var dropdown = document.getElementById(dropdownId);
    dropdown.classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function confirmDelete() {
    return confirm("Are you sure you want to delete this record?");
}

function confirmDeleteAll() {
    return confirm("Are you sure you want to delete all data?");
}

var existingMarkers = [];
var isExistingMarkerClicked = false;

document.addEventListener('DOMContentLoaded', function () {
    $.ajax({
        url: 'get_restricted_markers.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            existingMarkers = data;
            displayMarkers(existingMarkers);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching markers:', error);
        }
    });
});

map.on('popupopen', function (e) {
    // Set a flag to indicate that a popup is open
    isExistingMarkerClicked = true;
});

map.on('popupclose', function (e) {
    // Reset the flag for the next click
    isExistingMarkerClicked = false;
});

map.on('click', function (e) {
    var clickedLatLng = e.latlng;

    // Check if the click is within the vicinity of an existing marker
    var isClickOnExistingMarker = existingMarkers.some(function (marker) {
        var markerLatLng = L.latLng(marker.latitude, marker.longitude);
        var distance = clickedLatLng.distanceTo(markerLatLng);
        return distance <= marker.radius;
    });

    if (!isClickOnExistingMarker) {
        var latitude = e.latlng.lat;
        var longitude = e.latlng.lng;

        var markerPopup = L.popup({ closeButton: false, autoClose: false })
            .setLatLng([latitude, longitude])
            .setContent(getMarkerInputContent())
            .openOn(map);

        var locationNameInput = document.getElementById('locationNameInput');
        var radiusInput = document.getElementById('radiusInput');
        var descriptionInput = document.getElementById('descriptionInput');
        var addMarkerBtn = document.getElementById('addMarkerBtn');

        // Remove any existing click event listener to avoid multiple bindings
        addMarkerBtn.removeEventListener('click', onAddMarkerClick);

        // Add a new click event listener
        addMarkerBtn.addEventListener('click', onAddMarkerClick);

        function onAddMarkerClick() {
            addRestrictedMarker(latitude, longitude);
            markerPopup.setContent(getMarkerPopupContent(locationNameInput.value, parseFloat(radiusInput.value), latitude, longitude));
        }

        function getMarkerInputContent() {
            var content = '<b>Location:</b> <input type="text" id="locationNameInput" placeholder="Enter Location Name"><br>';
            content += '<b>Radius (meters):</b> <input type="text" id="radiusInput" placeholder="Enter Radius"><br>';
            content += '<b>Description:</b> <input type="text" id="descriptionInput" placeholder="Enter Description"><br>';
            content += '<br><button id="addMarkerBtn">Add Marker</button>';
            return content;
        }

        function addRestrictedMarker(latitude, longitude) {
            var locationName = locationNameInput.value;
            var description = descriptionInput.value;
            var radius = parseFloat(radiusInput.value);

            if (isNaN(radius)) {
                alert('Please enter a valid number for radius.');
                return;
            }

            var marker = L.marker([latitude, longitude], { icon: blackMarkerIcon }).addTo(map);

            var circle = L.circle([latitude, longitude], {
                radius: radius,
                color: 'black',
                fillColor: 'black',
                fillOpacity: 0.2
            }).addTo(map);

            marker.bindPopup(getMarkerPopupContent(locationName, description, radius, latitude, longitude)).openPopup();

            $.ajax({
                url: 'save_marker.php',
                method: 'POST',
                data: {
                    latitude: latitude,
                    longitude: longitude,
                    locationName: locationName,
                    radius: radius,
                    description: description
                },
                success: function (response) {
                    alert(response);
                },
                error: function (xhr, status, error) {
                    console.error('Error saving marker data:', error);
                }
            });

            existingMarkers.push({ latitude, longitude, radius });

            // Clear input values and close popup
            locationNameInput.value = '';
            descriptionInput.value = '';
            radiusInput.value = '';
            map.closePopup();
        }

        function getMarkerPopupContent(locationName, radius, latitude, longitude) {
            var content = '<b>Location:</b> ' + locationName + '<br>';
            content += '<b>Radius:</b> ' + radius + ' meters' +
                '<br><button class="delete-btn" onclick="deleteRestrictedMarker(' + latitude + ',' + longitude + ')">Delete</button>';
            return content;
        }
    }
});

function updateExistingMarkerRadius(latitude, longitude, newRadius) {
    existingMarkers.forEach(function (marker) {
        if (marker.latitude === latitude && marker.longitude === longitude) {
            marker.radius = newRadius;

            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker && layer.getLatLng().lat === latitude && layer.getLatLng().lng === longitude) {
                    map.removeLayer(layer);
                } else if (layer instanceof L.Circle && layer.getLatLng().lat === latitude && layer.getLatLng().lng === longitude) {
                    map.removeLayer(layer);
                }
            });

            var updatedMarker = L.marker([latitude, longitude], { icon: blackMarkerIcon }).addTo(map);
            var updatedCircle = L.circle([latitude, longitude], {
                radius: newRadius,
                color: 'black',
                fillColor: 'black',
                fillOpacity: 0.2
            }).addTo(map);

            updatedMarker.bindPopup(
                '<b>Location:</b> ' + marker.locationName + '<br><b>Radius:</b> ' + newRadius + ' meters' +
                '<br><button class="delete-btn" onclick="deleteRestrictedMarker(' + latitude + ',' + longitude + ')">Delete</button>'
            ).openPopup();
        }
    });
}

function displayMarkers(markers) {
    markers.forEach(function (markerInfo) {
        var marker = L.marker([markerInfo.latitude, markerInfo.longitude], { icon: blackMarkerIcon }).addTo(map);

        var circle = L.circle([markerInfo.latitude, markerInfo.longitude], {
            radius: markerInfo.radius,
            color: 'black',
            fillColor: 'black',
            fillOpacity: 0.2
        }).addTo(map);

        marker.bindPopup(
            '<b>Location:</b> ' + markerInfo.location_name + '<br><b>Radius:</b> ' + markerInfo.radius + ' meters' +
            '<br><button class="delete-btn" onclick="deleteRestrictedMarker(' + markerInfo.latitude + ',' + markerInfo.longitude + ')">Delete</button>'
        );
    });
}

var blackMarkerIcon = new L.Icon({
    iconUrl: '../images/marker-icon-2x-black.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

function deleteRestrictedMarker(latitude, longitude) {
    $.ajax({
        url: 'delete_restricted_area.php',
        method: 'POST',
        data: {
            latitude: latitude,
            longitude: longitude
        },
        success: function (response) {
            alert(response);
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error deleting marker:', error);
        }
    });
}

function goToBarangay(latitude, longitude) {
    map.setView([latitude, longitude], 13);

    var marker = L.marker([latitude, longitude]).addTo(map);

    var defaultRadius = 100;
    var circle = L.circle([latitude, longitude], {
        radius: defaultRadius,
        color: 'blue',
        fillColor: 'blue',
        fillOpacity: 0.2
    }).addTo(map);

    marker.bindPopup(
        barangayName +
        '<br><button class="delete-btn" onclick="deleteMarker(' + latitude + ',' + longitude + ')">Delete</button>'
    ).openPopup();
}

$(document).on('click', '#barangayDropdown a', function (e) {
    e.preventDefault();
    var latitude = parseFloat($(this).data('latitude'));
    var longitude = parseFloat($(this).data('longitude'));
    goToBarangay(latitude, longitude);
    toggleDropdown('barangayDropdown');
});

$.ajax({
    url: 'get_user_markers.php',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
        displayUserMarkers(data);
    },
    error: function (xhr, status, error) {
        console.error('Error fetching user markers:', error);
    }
});

function confirmUserMarker(markerId) {
    $.ajax({
        url: 'confirm_user_marker.php',
        method: 'POST',
        data: { id: markerId },
        success: function (response) {
            alert(response);
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error confirming user marker:', error);
        }
    });
}

var userMarkerIcon = new L.Icon({
    iconUrl: '../images/marker-icon-2x-green.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

function displayUserMarkers(markers) {
    markers.forEach(function (markerInfo) {
        var markerColor = markerInfo.status === 'Approved' ? 'red' : 'green';
        var markerIcon = new L.Icon({
            iconUrl: `../images/marker-icon-2x-${markerColor}.png`,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var marker = L.marker([markerInfo.latitude, markerInfo.longitude], { icon: markerIcon }).addTo(map);

        var popupContent = document.createElement('div');

        popupContent.innerHTML = `<div class="marker-popup">
            <b></b><span>${markerInfo.latitude}, ${markerInfo.longitude}</span><br>
            <b>Applicant Name:</b><span> ${markerInfo.name}</span><br>
            <b>Contact No:</b><span> ${markerInfo.contact_no}</span><br>
            <b>Type:</b><span> ${markerInfo.markerType}</span><br>
            <b>Location:</b><span> ${markerInfo.location}</span><br>
            <b>Status:</b><span> ${markerInfo.status}</span><br>
            <b>Date:</b><span> ${markerInfo.date}</span><br>
            <button class="delete-marker" onclick="deleteUserMarker(${markerInfo.id})">Delete</button>
            <button class="confirm-marker" onclick="confirmUserMarker(${markerInfo.id})">Confirm</button>
        </div>`;

        marker.bindPopup(popupContent);
    });
}

function deleteUserMarker(markerId) {
    $.ajax({
        url: 'delete_user_marker.php',
        method: 'POST',
        data: { id: markerId },
        success: function (response) {
            alert(response);
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error('Error deleting user marker:', error);
        }
    });
}
