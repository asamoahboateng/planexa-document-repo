// Initialize the map
var map = L.map('map').setView([43.7, -79.42], 13); // Default position (Toronto)

// Set up the tile layer (using OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: ''
}).addTo(map);

// Function to handle location search
function searchLocation() {
    var location = document.getElementById('locationInput').value;

    // Use OpenStreetMap Nominatim API to geocode the location
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var lat = data[0].lat;
                var lon = data[0].lon;

                // Update the map view to the new location
                map.setView([lat, lon], 13);

                // Add a marker on the new location
                L.marker([lat, lon]).addTo(map)
                    .bindPopup(`<b>${data[0].display_name}</b>`)
                    .openPopup();
            } else {
                alert('Location not found!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to get suggestions for location input
function getSuggestions() {
    var location = document.getElementById('locationInput').value;
    var suggestionsContainer = document.getElementById('suggestions');
    suggestionsContainer.innerHTML = '';  // Clear previous suggestions

    if (location.length < 3) {
        return; // Only start fetching when 3 or more characters are entered
    }

    // Fetch location suggestions
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}&addressdetails=1&limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(item => {
                    var suggestion = document.createElement('div');
                    suggestion.classList.add('suggestion-item');
                    suggestion.textContent = item.display_name;
                    suggestion.onclick = function() {
                        document.getElementById('locationInput').value = item.display_name;
                        searchLocation();  // Automatically search when a suggestion is selected
                        suggestionsContainer.innerHTML = '';  // Clear suggestions
                    };
                    suggestionsContainer.appendChild(suggestion);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching suggestions:', error);
        });
}